<?php

namespace App\Services\Crawler;

use App\Models\Post;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class AiGenerate
{
    private string $prompt = <<<'PROMPT'
请基于以下原文内容，生成一篇新的英文新闻稿，要求：

【核心写作要求】
1. 必须完整保留原文中的事实信息、事件时间、地点、人物身份及引语内容，不得篡改原意。
2. 不得添加原文未提及的新事实、推测、背景解释或主观判断。
3. 写作视角应为“事件发生后不久的现场新闻报道”，语言克制、客观。
4. 重点描写已发生的行为、现场情况和被报道的反应，避免抽象总结或概念化分析。
5. 禁止使用百科式、教材式或说明性写法（如定义术语、系统解释背景）。

【结构与语言风格】
6. 不要使用任何小标题或分节标签（如“背景”“政治反应”“社区动员”等）。
7. 段落长度需自然变化，可包含较短段落（1–2 句）与较长段落混合。
8. 允许存在轻微重复、节奏不均或不完美衔接，以符合真实新闻写作特征。
9. 避免使用常见 AI 或编辑型过渡语，包括但不限于：
   - “This highlights…”
   - “It is important to note…”
   - “In conclusion…”
   - “The incident underscores…”
   - “From a broader perspective…”

【HTML 与内容规范】
10. 正文仅使用 <p> 标签，不使用 <h1>-<h6>、<img> 或其他标签。
11. 正文中不得再次出现文章标题。
12. 不包含作者信息、媒体来源、图片说明、“阅读更多”等内容。
13. 内容需明显区别于原文表达，文字相似度控制在 30% 以下，但信息完整性不得降低。

【额外生成内容】
14. 生成一个新的英文新闻标题（避免使用冒号结构）。
15. 生成一段英文摘要（summary），不超过 230 字节。
16. 生成 2–4 个与事件高度相关的英文 tags。

【输出格式】
17. 请严格按照以下 JSON 格式返回结果，不要包含任何解释性文字。
18. 全文必须为英文。

```json
{
    "title": "生成的文章标题",
    "summary": "生成文章的简介(少于250字节)",
    "content": "改写后的文章内容...",
    "tags": ["..", ...]
}```

原文：
PROMPT;

    private Client $client;

    public function __construct(
        public Post $post
    ) {
        $this->client = new Client([
            'headers' => [
                'Authorization' => 'Bearer '.env('DEEPSEEK_SECRET'),
                'Content-Type' => 'application/json',
            ],
            'timeout' => 60,
        ]);
    }

    public function generate(): ?Post
    {
        try {
            $response = $this->client->post('https://api.deepseek.com/chat/completions', [
                'json' => [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $this->prompt.$this->post->content,
                        ],
                    ],
                    'temperature' => 0.9,
                    'max_tokens' => 4000,
                    'response_format' => ['type' => 'json_object'],
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            dump($e->getMessage());
            Log::error("AI Generate Post Failed: {$e->getMessage()}");

            return null;
        }
        $data = json_decode($response, true);
        if (isset($data['choices'][0]['message']['content'])) {
            $arr = json_decode($data['choices'][0]['message']['content'], true);

            $this->post->title = $arr['title'];
            $this->post->summary = $arr['summary'];
            $this->post->content = $arr['content'];
            $this->post->tags = $arr['tags'];

            return $this->post;
        }

        return null;
    }
}
