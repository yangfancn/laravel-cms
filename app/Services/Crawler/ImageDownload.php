<?php

namespace App\Services\Crawler;

use App\Models\Image;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\ImageManager;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Symfony\Component\DomCrawler\Crawler;

class ImageDownload
{
    private ImageHash $hashHandler;
    private ImageManager $imageManager;

    public function __construct(
        private ImageConfig $config,
        private ?ClientInterface $client = null
    ) {
        $this->client = $this->client ?: new Client();
        $this->hashHandler = new ImageHash(new DifferenceHash());
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * 下载单个图片
     * @throws \Exception
     */
    public function download(string $url, ?string $saveName = null): ImageDownloadResult
    {
        try {
            $imageContent = $this->fetchImage($url);
            $path = $this->getStoragePath();
            $filename = $this->generateFilename($url, $saveName);

            // 保存原始图片
            $fullPath = $this->saveOriginalImage($imageContent, $path, $filename);

            if (!in_array($this->getExtensionFromUrl($fullPath), ['svg', 'gif'])) {
                // 验证和处理图片
                $this->validateAndProcessImage($fullPath);
            }

            // 查找或创建图片记录
            return $this->findOrCreateImageRecord($url, $path, $filename, $fullPath);
        } catch (\Exception $e) {
            return new ImageDownloadResult($url, error: $e->getMessage());
        }
    }

    /**
     * 从HTML中下载所有图片
     */
    public function downloadFromHtml(string $html, string $attr = 'src'): string
    {
        if (empty($html)) {
            return $html;
        }

        $attributes = explode('|', $attr);
        $crawler = new Crawler($html);

        $crawler->filter('img')->each(function (Crawler $node) use ($attributes) {
            $url = $this->extractImageUrl($node, $attributes);

            if (!$url) {
                return;
            }

            try {
                $result = $this->download($url);
                if (!$result->error) {
                    $this->updateImageNode($node, $result->public_path, $attributes);
                }
            } catch (\Exception) {
                // 记录日志但继续处理其他图片
            }
        });

        return $crawler->filter('body')->html();
    }

    /**
     * 获取图片存储路径
     */
    private function getStoragePath(): string
    {
        $path = $this->config->saveDir . DIRECTORY_SEPARATOR;
        if ($this->config->dateDirFormat) {
            $path .= date($this->config->dateDirFormat) . DIRECTORY_SEPARATOR;
        }
        return $path;
    }

    /**
     * 生成文件名
     */
    private function generateFilename(string $url, ?string $saveName): string
    {
        if ($saveName) {
            return $saveName;
        }

        $ext = $this->getExtensionFromUrl($url);
        return Str::random(40) . '.' . $ext;
    }

    /**
     * 获取文件扩展名
     */
    private function getExtensionFromUrl(string $url): string
    {
        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'png', 'gif', 'svg', 'jpeg', 'webp', 'svg']) ? $ext : 'png';
    }

    /**
     * 从远程获取图片内容
     * @throws GuzzleException
     */
    private function fetchImage(string $url): string
    {
        return $this->client
            ->request('GET', $url, $this->config->requestOptions)
            ->getBody()
            ->getContents();
    }

    /**
     * 保存原始图片
     */
    private function saveOriginalImage(string $content, string $path, string $filename): string
    {
        Storage::disk($this->config->disk)->put($path . $filename, $content);
        return Storage::disk($this->config->disk)->path($path . $filename);
    }

    /**
     * 验证和处理图片
     * @throws RuntimeException
     */
    private function validateAndProcessImage(string $fullPath): void
    {
        $image = $this->imageManager->read($fullPath);

        if ($this->config->maxWidth || $this->config->maxHeight || $this->config->aspectRatio) {
            $this->resizeImage($fullPath, $image);
        }
    }

    /**
     * 调整图片大小
     */
    private function resizeImage(string $fullPath, \Intervention\Image\Image $image): void
    {
        $width = $image->width();
        $height = $image->height();

        // 处理宽高比
        if ($this->config->aspectRatio) {
            $this->cropToAspectRatio($image, $width, $height);
            if (!$this->config->maxWidth && !$this->config->maxHeight) {
                $image->save($fullPath);
                return;
            }
        }

        // 处理最大宽度
        if ($this->config->maxWidth && $width > $this->config->maxWidth) {
            $image->scaleDown(width: $this->config->maxWidth);
            $image->save($fullPath);
            return;
        }

        // 处理最大高度
        if ($this->config->maxHeight && $height > $this->config->maxHeight) {
            $image->scaleDown(height: $this->config->maxHeight);
            $image->save($fullPath);
        }
    }

    /**
     * 按宽高比裁剪图片
     */
    private function cropToAspectRatio(\Intervention\Image\Image $image, int $width, int $height): void
    {
        $aspectHeight = ceil($width / $this->config->aspectRatio);

        if ($aspectHeight < $height) {
            $cropWidth = $width;
            $cropHeight = $aspectHeight;
            $x = 0;
            $y = floor(($height - $aspectHeight) / 2);
        } else {
            $cropWidth = ceil($height * $this->config->aspectRatio);
            $cropHeight = $height;
            $x = floor(($width - $cropWidth) / 2);
            $y = 0;
        }

        $image->crop($cropWidth, $cropHeight, $x, $y);
    }

    /**
     * 查找或创建图片记录
     */
    private function findOrCreateImageRecord(string $url, string $path, string $filename, string $fullPath): ImageDownloadResult
    {
        /**
         * @var \Illuminate\Filesystem\LocalFilesystemAdapter $disk
         */
        $disk = Storage::disk($this->config->disk);
        $publicPath = $disk->url($path . $filename);

        try {
            $imageHash = $this->hashHandler->hash($fullPath);
            $imageModel = Image::firstOrCreate(
                ['hash' => $imageHash],
                ['path' => $publicPath]
            );
            if (!$imageModel->wasRecentlyCreated) {
                Storage::disk($this->config->disk)->delete($path . $filename);
                return new ImageDownloadResult(
                    $url,
                    Storage::disk($this->config->disk)->path($imageModel->path),
                    $imageModel->path
                );
            }
        } catch (RuntimeException $exception) {
            //不是普通图片，例如svg，无法生成hash
        }

        return new ImageDownloadResult($url, $fullPath, $publicPath);
    }

    /**
     * 从节点提取图片URL
     */
    private function extractImageUrl(Crawler $node, array $attributes): ?string
    {
        foreach ($attributes as $attr) {
            $source = $node->attr($attr);
            if ($source && !str_starts_with($source, 'data:image')) {
                return $source;
            }
        }
        return null;
    }

    /**
     * 更新图片节点属性
     */
    private function updateImageNode(Crawler $node, string $newSrc, array $attributes): void
    {
        $imgNode = $node->getNode(0);
        if ($imgNode instanceof \DOMElement) {
            $imgNode->setAttribute('src', $newSrc);
            foreach ($attributes as $attr) {
                if ($attr !== 'src') {
                    $imgNode->removeAttribute($attr);
                }
            }
        }
    }
}
