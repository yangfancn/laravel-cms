# 项目概览（草稿）

本文档为项目的快速使用指南与开发者说明的草稿版，分为“后台（Admin）”与“前端（Frontend）”两部分。你可在此基础上补全细节与截图。

**注意**：以下文件路径可参考项目源码以获取更详细实现： [routes/admin.php](routes/admin.php), [routes/web.php](routes/web.php), [app/Providers/SlugServiceProvider.php](app/Providers/SlugServiceProvider.php), [app/Http/Renderers](app/Http/Renderers), [app/Services/Seo.php](app/Services/Seo.php), [app/Services/Form](app/Services/Form).

--

**目录**

- 后台
	- 项目初始化及运行
	- 后端登录地址
	- 权限管理简介
	- 表单（Form）概览
	- 添加资源的标准步骤
- 前端
	- Slug renderer 机制说明
	- 如何添加资源对应的 renderer
	- `App\Services\Seo` 简介

--

## 后台

- **项目初始化及运行**

	- 安装依赖（按项目需要）

		```bash
		composer install
		npm install # or pnpm install
		```

	- 运行数据库迁移并填充示例数据：

		```bash
		php artisan migrate --seed
		```

	- 本地启动（或使用你现有的部署方式）：

		```bash
		php artisan serve
		```

- **后端登录地址**

	- 默认为`/manager`, 可以在 [routes/admin.php](routes/admin.php) 中修改

- **权限管理（简介）**

	管理地址 `/manager/permissions`, 初始化项目位于 [database/seeders/AuthSeeder.php](database/seeders/AuthSeeder.php)，需要在后台左侧菜单显示的需要填写表单的Admin Menu

- **表单（Form）概览**

	- 相关代码位于 `App\Services\Form`

	常用元素示例（示范如何在 `FormBuilder::schema()` 中使用）：

	- 基本用法参考 [app/Forms/Admin/PostForm.php](app/Forms/Admin/PostForm.php)

    - **表单基础** [app/Services/Form/Elements/Element.php](app/Services/Form/Elements/Element.php)

        所有表单组件都继承 `Element`，以下方法在所有组件中可用：

        - `::make(string $name, string|bool $label)` 所有输入组件的开始
            - `$name` 对应表单提交的字段名字，已经在fillData 中的属性名
            - `$label` 显示在页面的 Label，`null|false` 不显示
        - `disable()` 禁用
        - `cols(int $cols)` 在页面上的横向占比 `$cols/12`
        - `autofocus(bool $auto = true)` 页面打开时的自动聚焦，一个表单应该只有一个 `autofocus`
        - `defaultValue(mixed $defaultValue)` 设置默认值

    - **Input** 文本输入 [app/Services/Form/Elements/Input.php](app/Services/Form/Elements/Input.php)

        - `email()` 设置为邮箱输入类型
        - `number()` 设置为数字输入类型
        - `password()` 设置为密码输入类型
        - `textarea()` 设置为多行文本输入类型
        - `autocomplete(string|true $auto = true)` 允许自动填充，`true` 时使用字段名，或传入具体的自动完成属性
        - `mask(string $mask)` 输入格式化，例如 `####-##-##`（数字）、`SNNN-SSSS`（混合）、`AaaXxx`（字母）等
        - `fillMask(bool $fill = true)` 设置是否自动填充 mask
        - `clearable(bool $able = true)` 显示清除按钮
        - `clearIcon(string $icon)` 设置清除按钮图标
        - `counter(bool $counter = true)` 显示字符计数器
        - `appendIcon(string $icon)` 在输入框后添加图标
        - `prefix(string $str)` 在输入框前添加前缀文本（如货币符号）
        - `suffix(string $str)` 在输入框后添加后缀文本
        - `prependIcon(string $icon)` 在输入框前添加图标
        - `labelColor(string $color)` 设置标签颜色
        - `bgColor(string $color)` 设置背景颜色
        - `color(string $color)` 设置文本颜色
        - `rounded(bool $round = true)` 圆角样式
        - `square(bool $square = true)` 方角样式
        - `centerAffix(bool $align)` 居中对齐前后缀
        - `variant(Variant $variant)` 设置样式变体（standard、filled、outlined 等）

        示例：

        ```php
        Input::make('email', 'Email Address')
            ->email()
            ->autocomplete()
            ->appendIcon('email')
            ->clearable()
            ->cols(6);
        ```

    - **Select** 下拉选择 [app/Services/Form/Elements/Select.php](app/Services/Form/Elements/Select.php)

        - `options(array $options)` 设置选项，支持分组和键值对格式
        - `maxLength(int $length)` 多选时最大可选数量限制
        - `xhrOptionsUrl(string $url)` 异步加载选项的远程 URL（远程搜索）
        - `allowCreate(?string $url = null)` 允许通过输入创建新选项，可传创建接口 URL
        - `dropdownIcon(string $icon)` 设置下拉箭头图标
        - `clearable(bool $able = true)` 显示清除按钮
        - `clearIcon(string $icon)` 设置清除按钮图标
        - `counter(bool $counter = true)` 显示计数器
        - `appendIcon(string $icon)` 在下拉框后添加图标
        - `prefix(string $str)` 在下拉框前添加前缀文本
        - `suffix(string $str)` 在下拉框后添加后缀文本
        - `prependIcon(string $icon)` 在下拉框前添加图标
        - `multiple(bool $multiple = true)` 启用多选模式
        - `labelColor(string $color)` 设置标签颜色
        - `bgColor(string $color)` 设置背景颜色
        - `color(string $color)` 设置文本颜色
        - `rounded(bool $round = true)` 圆角样式
        - `square(bool $square = true)` 方角样式
        - `centerAffix(bool $align)` 居中对齐前后缀
        - `variant(Variant $variant)` 设置样式变体
        - `useChips(bool $chips = true)` 多选时以 chip 形式显示
        - `useInput(bool $as = true)` 启用搜索输入框

        示例：

        ```php
        Select::make('category_id', 'Category')
            ->options([1 => 'News', 2 => 'Tutorial'])
            ->multiple()
            ->useChips()
            ->useInput()
            ->cols(6);
        ```

    - **Toggle** 开关 [app/Services/Form/Elements/Toggle.php](app/Services/Form/Elements/Toggle.php)

        - `icon(string $icon)` 自定义图标名称
        - `iconColor(string $color)` 设置图标颜色
        - `size(string $iconSize)` 设置图标大小（xs、md、lg 等或具体尺寸）
        - `color(string $color)` 设置开关颜色
        - `checkedIcon(string $icon)` 选中状态的图标
        - `unCheckedIcon(string $icon)` 未选中状态的图标
        - `keepColor(bool $keepColor = true)` 保持选中状态的颜色
        - `leftLabel(bool $leftLabel = true)` 标签显示在左边（true）或右边（false）

        示例：

        ```php
        Toggle::make('is_featured', 'Featured')
            ->defaultValue(false)
            ->icon('check')
            ->iconColor('green')
            ->size('md')
            ->leftLabel(true);
        ```

    - **Checkbox** 复选 [app/Services/Form/Elements/Checkbox.php](app/Services/Form/Elements/Checkbox.php)

        - `options(array $options)` 设置复选项，支持 `['value' => 'Label']` 格式
        - `column(bool $column = true)` 垂直显示选项（true）或水平显示（false）
        - `size(string $iconSize)` 设置复选框大小
        - `color(string $color)` 设置复选框颜色
        - `checkedIcon(string $icon)` 选中状态的图标
        - `unCheckedIcon(string $icon)` 未选中状态的图标
        - `keepColor(bool $keepColor = true)` 保持选中状态的颜色
        - `leftLabel(bool $leftLabel = true)` 标签显示在左边还是右边

        示例：

        ```php
        Checkbox::make('tags', 'Tags')
            ->options(['laravel' => 'Laravel', 'php' => 'PHP'])
            ->column()
            ->color('blue')
            ->size('lg');
        ```

    - **DatePicker** 日期选择 [app/Services/Form/Elements/DatePicker.php](app/Services/Form/Elements/DatePicker.php)

        （继承自 `DatetimePicker`，只包含日期，不含时间）

        - `range(bool $range = true)` 开启日期区间选择模式，选中值为 `['from' => null, 'to' => null]`
        - `appendIcon(string $icon)` 在日期框后添加图标
        - `prefix(string $str)` 在日期框前添加前缀文本
        - `suffix(string $str)` 在日期框后添加后缀文本
        - `prependIcon(string $icon)` 在日期框前添加图标
        - `labelColor(string $color)` 设置标签颜色
        - `bgColor(string $color)` 设置背景颜色
        - `color(string $color)` 设置文本颜色
        - `rounded(bool $round = true)` 圆角样式
        - `square(bool $square = true)` 方角样式
        - `centerAffix(bool $align)` 居中对齐前后缀
        - `variant(Variant $variant)` 设置样式变体
        - `useInput(bool $as = true)` 允许直接在输入框中输入日期

        示例：

        ```php
        DatePicker::make('published_at', 'Publish Date')
            ->defaultValue(now()->toDateString())
            ->range()
            ->useInput()
            ->cols(6);
        ```

    - **TimePicker** 时间选择 [app/Services/Form/Elements/TimePicker.php](app/Services/Form/Elements/TimePicker.php)

        （继承自 `DatetimePicker`，只包含时间，不包含日期）

        - 支持与 `DatePicker` 相同的所有方法（appendIcon、prefix、suffix、prependIcon、color、rounded、useInput 等）

    - **ColorPicker** 颜色选择 [app/Services/Form/Elements/ColorPicker.php](app/Services/Form/Elements/ColorPicker.php)

        - `palette(array $arr)` 指定颜色预设面板，例如 `['#ff0000', '#00ff00', '#0000ff']`
        - `defaultView(ColorView $view)` 设置默认视图（SPECTRUM、PALETTE、TUNER 等）
        - `noHeader(bool $noHeader = true)` 隐藏颜色选择器的头部
        - `noHeaderTabs(bool $noHeaderTabs = true)` 隐藏头部的选项卡
        - `noFooter(bool $noFooter = true)` 隐藏颜色选择器的底部
        - `appendIcon(string $icon)` 在颜色框后添加图标
        - `prefix(string $str)` 在颜色框前添加前缀文本
        - `suffix(string $str)` 在颜色框后添加后缀文本
        - `prependIcon(string $icon)` 在颜色框前添加图标
        - `labelColor(string $color)` 设置标签颜色
        - `bgColor(string $color)` 设置背景颜色
        - `color(string $color)` 设置文本颜色
        - `rounded(bool $round = true)` 圆角样式
        - `square(bool $square = true)` 方角样式
        - `centerAffix(bool $align)` 居中对齐前后缀
        - `variant(Variant $variant)` 设置样式变体
        - `useInput(bool $as = true)` 允许在输入框中输入十六进制颜色值

        示例：

        ```php
        ColorPicker::make('theme_color', 'Theme Color')
            ->palette(['#ff0000', '#00ff00', '#0000ff'])
            ->useInput()
            ->cols(4);
        ```

    - **Uploader** 上传 [app/Services/Form/Elements/Uploader.php](app/Services/Form/Elements/Uploader.php)

        - `disable(bool $disable = true)` 禁用上传功能
        - `maxFiles(int $maxFiles = 1)` 最大上传文件数（>1 时自动启用多文件模式）
        - `allowReorder()` 允许对已上传文件重新排序
        - `accept(string $accept)` 接受的文件类型，例如 `image/*`、`.pdf, .docx` 等
        - `cropper(float|int|null $aspectRatio = null)` 启用图片裁剪功能，可设置长宽比（如 1.0 表示正方形）

        示例：

        ```php
        Uploader::make('thumb', 'Thumbnail')
            ->maxFiles(1)
            ->accept('image/*')
            ->cropper(1.0)
            ->cols(6);
        ```

    - **FilePicker** 文件选择 [app/Services/Form/Elements/FilePicker.php](app/Services/Form/Elements/FilePicker.php)

        - `prependIcon(string $icon = 'cloud_upload')` 设置前置图标
        - `clearable(bool $able = true)` 显示清除按钮
        - `clearIcon(string $icon)` 设置清除按钮图标
        - `appendIcon(string $icon)` 在输入框后添加图标
        - `prefix(string $str)` 在输入框前添加前缀文本
        - `suffix(string $str)` 在输入框后添加后缀文本
        - `multiple(bool $multiple = true)` 允许选择多个文件
        - `labelColor(string $color)` 设置标签颜色
        - `bgColor(string $color)` 设置背景颜色
        - `color(string $color)` 设置文本颜色
        - `rounded(bool $round = true)` 圆角样式
        - `square(bool $square = true)` 方角样式
        - `centerAffix(bool $align)` 居中对齐前后缀
        - `variant(Variant $variant)` 设置样式变体
        - `useChips(bool $chips = true)` 以 chip 形式显示已选文件
        - `accept(string $accept)` 接受的文件类型（如 `.pdf, .docx` 或 `image/*`）
        - `maxFileSize(int $size)` 单个文件最大大小（字节）
        - `maxTotalSize(int $size)` 所有文件总大小限制（字节）
        - `maxFiles(int $length)` 最大文件数

        示例：

        ```php
        FilePicker::make('attachments', 'Attachments')
            ->multiple()
            ->accept('.pdf, .docx')
            ->maxFiles(5)
            ->maxFileSize(5242880)
            ->useChips()
            ->clearable()
            ->cols(12);
        ```

    - **Slider** 单值滑块 [app/Services/Form/Elements/Slider.php](app/Services/Form/Elements/Slider.php)

        - `snap(bool $snap = true)` 启用快照，滑块只能停留在整数位置
        - `step(float|int $step = 1)` 滑块移动的步长
        - `min(float|int $min = 0)` 最小值
        - `max(float|int $max)` 最大值
        - `reverse(bool $reverse = true)` 反向方向
        - `vertical(bool $vertical = true)` 垂直显示（true）或水平显示（false）
        - `labelAlways(bool $always = true)` 始终显示标签
        - `innerMin(float|int $innerMin)` 内侧最小值
        - `innerMax(float|int $innerMax)` 内侧最大值
        - `thumbSize(string $size)` 设置滑块大小（如 `12px`、`1rem`）
        - `thumbColor(string $color)` 设置滑块颜色
        - `trackSize(string $size)` 设置轨道大小
        - `trackColor(string $color)` 设置轨道颜色
        - `innerTrackColor(string $color)` 设置内侧轨道颜色
        - `selectionColor(string $color)` 设置选中部分颜色
        - `labelColor(string $color)` 设置标签背景颜色
        - `labelTextColor(string $color)` 设置标签文本颜色
        - `switchLabelSide(bool $side = true)` 切换标签显示在滑块的哪一侧

    - **Range** 区间滑块 [app/Services/Form/Elements/Range.php](app/Services/Form/Elements/Range.php)

        - `defaultValue` 为 `['min' => null, 'max' => null]`
        - 支持 Slider 的所有方法
        - `leftLabelColor(string $color)` 设置左侧标签背景颜色
        - `leftLabelTextColor(string $color)` 设置左侧标签文本颜色
        - `rightLabelColor(string $color)` 设置右侧标签背景颜色
        - `rightLabelTextColor(string $color)` 设置右侧标签文本颜色
        - `leftThumbColor(string $color)` 设置左侧滑块颜色
        - `rightThumbColor(string $color)` 设置右侧滑块颜色
        - `switchLabelSide(bool $side = true)` 切换标签显示位置

    - **Radio** 单选 [app/Services/Form/Elements/Radio.php](app/Services/Form/Elements/Radio.php)

        - `options(array $options)` 设置单选项，支持 `['value' => 'Label']` 格式
        - `column(bool $column = true)` 垂直显示选项（true）或水平显示（false）
        - `size(string $iconSize)` 设置单选框大小
        - `color(string $color)` 设置单选框颜色
        - `checkedIcon(string $icon)` 选中状态的图标
        - `unCheckedIcon(string $icon)` 未选中状态的图标
        - `keepColor(bool $keepColor = true)` 保持选中状态的颜色
        - `leftLabel(bool $leftLabel = true)` 标签显示在左边还是右边

        示例：

        ```php
        Radio::make('status', 'Status')
            ->options(['active' => 'Active', 'inactive' => 'Inactive'])
            ->column()
            ->color('teal')
            ->size('md');
        ```

    - **Editor** 富文本编辑器（CKEditor5） [app/Services/Form/Elements/Editor.php](app/Services/Form/Elements/Editor.php)

        - `toolbar(array $items)` 配置工具栏插件，支持的项目有：`undo`, `redo`, `heading`, `showBlocks`, `alignment`, `blockQuote`, `bulletedList`, `numberedList`, `todoList`, `link`, `horizontalLine`, `bold`, `italic`, `fontColor`, `fontBackgroundColor`, `fontFamily`, `fontSize`, `underline`, `strikethrough`, `subscript`, `superscript`, `outdent`, `indent`, `imageUpload`, `insertTable`, `mediaEmbed`, `code`, `codeBlock`, `sourceEditing` 等
        - `minHeight(string $minHeight)` 设置编辑器最小高度（如 `400px`）
        - `counter(bool $counter = true)` 显示字符计数器

        示例：

        ```php
        Editor::make('content', 'Content')
            ->toolbar(['bold', 'italic', 'link', 'heading', 'imageUpload', 'blockQuote'])
            ->minHeight('400px')
            ->counter()
            ->cols(12);
        ```


--
- **添加资源的标准步骤（建议）**

	1. 生成迁移、模型、控制器、请求和策略

		 ```bash
		 php artisan make:model Post -m
		 php artisan make:controller Admin/PostController --resource
		 php artisan make:request StorePostRequest
		 php artisan make:policy PostPolicy --model=Post
		 ```

	2. 在 `routes/admin.php` 中注册资源路由（或在控制器中按现有风格追加）

	3. 在权限表中创建对应的权限记录（`permissions`）并在 `menu`/`admin menu` 配置中添加菜单项，方便在后台导航展示。

	4. 在相应的视图中使用 `App\Services\Form` 的表单组件来构建表单，以及使用项目的 `datatable` 组件（位于 `App\Services`）来渲染列表页面。

	5. 在策略（Policy）中实现授权，并在 `PermissionController` 中将新权限分配给角色。

--

## 前端

- **Slug renderer 概述**

	- 项目通过 `Slug` 模型与一组“渲染器（renderers）”来处理基于 slug 的页面路由。主要组件位于 [app/Http/Renderers](app/Http/Renderers)：
		- `SlugRenderManager`：负责遍历已注册的 `SlugRenderer`，找出能处理当前 `sluggable` 实例的渲染器并返回渲染结果。
		- `CategoryRenderer`, `PostRenderer`, `TagRenderer`：各模型对应的渲染器实现。
		- `CategoryRenderer` 内部进一步委托 `CategorySubRenderer`（例如 `CategoryPostsRenderer`, `CategoryViewRenderer`）来按 `Category::type` 选择具体渲染方式。

	- 路由请见 [routes/web.php](routes/web.php)，`{slug}` 路由映射到 `SlugController`（或 `Home\\SlugController`），并通过 `SlugRenderManager` 完成最终视图返回。

- **如何添加资源对应的 renderer（步骤）**

	1. 新建渲染器类，实现 `App\\Http\\Renderers\\Contracts\\SlugRenderer`（或 `CategorySubRenderer` 用于分类子渲染器）。示例骨架：

		 ```php
		 <?php
		 namespace App\\Http\\Renderers;

		 use App\\Http\\Renderers\\Contracts\\SlugRenderer;
		 use Illuminate\\Http\\Response;

		 class MyResourceRenderer implements SlugRenderer
		 {
				 public function supprots(object $target): bool
				 {
						 // 判断是否能处理 $target（模型实例或类型）
				 }

				 public function renderer(object $target): Response
				 {
						 // 处理并返回 response()->view(...)
				 }
		 }
		 ```

	2. 在 `app/Providers/SlugServiceProvider.php` 中注册新的渲染器标签（示例已存在）：

		 ```php
		 $this->app->tag([
				 CategoryRenderer::class,
				 PostRenderer::class,
				 TagRenderer::class,
				 MyResourceRenderer::class, // 新增
		 ], SlugRenderer::class);
		 ```

	3. 如为 `Category` 的新类型（`CategoryType`），在 `CategoryRenderer` 的子渲染器集合中注册对应的 `CategorySubRenderer`，并确保其 `supports()` 实现能按 `->type` 正确匹配。

	4. 在本地环境或 CI 中通过访问对应 slug（或添加单元测试）验证渲染流程。

- **`App\\Services\\Seo` 简介**

	- `App\\Services\\Seo` 为集中化的 SEO 元数据管理器，支持：
		- 以模型为来源生成 meta（模型需使用 `App\\Models\\Traits\\Metable`）
		- 手动设置 title/keywords/description/url/image
		- 将 SEO 数据共享到视图层，最终通过 `home::seo` 视图片段渲染

	- 常见用法示例：在渲染器中：

		```php
		// model has meta
		App\\Facades\\Seo::model($model);

		// or custom
		App\\Facades\\Seo::seo('Title', description: '...');
		```

--

## 附录：建议改进点（供你在完善文档时考虑）

- 在 `SlugServiceProvider` 中增加启动时验证（log 已注册的 renderers），便于发现未注册的 renderer。
- 对找不到 renderer 的情况抛出明确的 500 错误（例如 `RendererNotFoundException`），并在 `SlugController` 中统一捕获，输出友好提示或错误页面。
- 在 `app/Services/Form` 下为每个表单元素补充示例与属性说明，便于后台页面搭建者直接复用。

--

这份 README 是草稿版。我已经把关键文件位置和核心流程写清楚，后续你可以：

- 补充每个表单组件的参数和示例代码片段；
- 明确后台登录实际路径并截图；
- 添加渲染器添加的完整示例（含单元测试或 cURL 请求）；
- 若需要，我可以把本草稿细化为英文版或添加图表/流程图。
