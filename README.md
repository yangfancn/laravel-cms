# 🚀 Laravel 全栈应用

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red?style=flat-square&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green?style=flat-square&logo=vue.js)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.5+-purple?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)](LICENSE)

这是一个基于 Laravel 12 构建的全栈 Web 应用，包含完整的后台管理系统和基于 Slug 的前端渲染系统。项目集成了现代化的前端技术栈，包括 Vue 3、Quasar UI、Tailwind CSS 等先进工具。

## 🚀 技术栈

### 后端技术

- **Laravel 12** - PHP 框架
- **PHP 8.5+** - 编程语言
- **SQLite** - 默认数据库（支持 MySQL/PostgreSQL）
- **Spatie Laravel Permission** - 权限管理
- **Spatie Laravel MediaLibrary** - 文件管理
- **Laravel Activitylog** - 操作日志
- **Inertia.js** - 全栈 SPA 框架

### 前端技术

- **Vue 3** - JavaScript 框架
- **Quasar** - UI 组件库
- **Tailwind CSS** - CSS 框架
- **CKEditor 5** - 富文本编辑器
- **Vite** - 构建工具
- **TypeScript** - 类型支持

## 📋 功能特性

### 后台管理

- 🔐 完整的权限管理系统
- 📝 自定义表单构建器
- 📊 数据表格组件
- 📁 文件上传与管理
- 🎨 富文本编辑器
- 📱 响应式界面

### 前端渲染

- 🌐 基于 Slug 的路由系统
- 🎭 灵活的渲染器机制
- 🔍 SEO 优化管理
- 📱 移动端适配
- ⚡ 页面缓存

## 📚 目录

- [项目结构](#项目结构)
- [环境准备](#环境准备)
- [后台管理](#后台管理)
    - [项目初始化及运行](#项目初始化及运行)
    - [后台管理入口](#后台管理入口)
    - [权限管理系统](#权限管理系统)
    - [自定义表单系统](#自定义表单系统)
    - [添加资源的标准步骤](#添加资源的标准步骤)
- [前端渲染](#前端渲染)
    - [Slug renderer 机制](#slug-renderer-机制)
    - [如何添加资源对应的 renderer](#如何添加资源对应的-renderer)
    - [SEO 管理服务](#seo-管理服务)
- [开发指南](#开发指南)
- [部署说明](#部署说明)

---

## 项目结构

```
LaravelApp/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # 控制器
│   │   ├── Renderers/           # 前端渲染器
│   │   └── Requests/            # 表单请求验证
│   ├── Models/                  # 数据模型
│   ├── Services/                # 业务服务
│   │   ├── Form/               # 表单构建系统
│   │   └── Seo.php             # SEO 服务
│   ├── Forms/                   # 表单定义
│   ├── Providers/               # 服务提供者
│   └── Enums/                   # 枚举类
├── database/
│   ├── migrations/              # 数据库迁移
│   ├── seeders/                # 数据填充
│   └── factories/              # 模型工厂
├── resources/
│   ├── admin/                   # 后台管理资源
│   │   ├── views/              # Blade 布局文件
│   │   ├── js/
│   │   │   ├── Pages/          # Inertia.js 页面组件
│   │   │   │   ├── Auth/      # 认证相关页面
│   │   │   │   ├── Dashboard/ # 仪表板页面
│   │   │   │   ├── Users/     # 用户管理页面
│   │   │   │   ├── Session/   # 会话管理页面
│   │   │   │   └── Errors/    # 错误页面
│   │   │   ├── Components/     # Vue 组件
│   │   │   └── Layouts/        # 布局组件
│   │   └── images/             # 图片资源
│   ├── home/                    # 前端展示资源
│   │   ├── views/              # Blade 模板文件
│   │   │   ├── components/    # 组件模板
│   │   │   ├── posts/         # 文章页面
│   │   │   ├── categories/    # 分类页面
│   │   │   └── tags/          # 标签页面
│   │   ├── js/
│   │   │   └── Plugins/       # 前端插件
│   │   │       ├── Comments/  # 评论组件
│   │   │       └── Vote/      # 投票组件
│   │   ├── css/               # 样式文件
│   │   └── images/            # 图片资源
│   └── common/                  # 共享资源
│       ├── js/                # 公共 JavaScript
│       └── css/               # 公共样式
├── routes/
│   ├── web.php                 # 前端路由
│   └── admin.php               # 后台路由
└── storage/
    └── app/uploads/            # 上传文件目录
```

### Inertia.js 页面架构

后台管理页面使用 Inertia.js + Vue 3 构建单页应用：

- **Pages 目录**：`resources/admin/js/Pages/` 包含所有页面组件
- **路由到页面映射**：Laravel 路由自动映射到对应的 Vue 页面
- **组件化开发**：每个功能模块都有独立的页面组件
- **共享数据**：通过 Inertia 中间件共享用户数据、权限等信息

### 前端渲染架构

前端使用传统的 Blade 模板 + Vue 组件混合架构：

- **Blade 布局**：`resources/home/views/` 处理页面结构和 SEO
- **Vue 插件**：`resources/home/js/Plugins/` 提供交互功能
- **组件化**：可复用的 Vue 组件用于评论、投票等功能

---

## 环境准备

### 环境要求

- PHP 8.5+
- Composer
- Node.js 18+
- NPM 或 Yarn

---

## 后台管理

- **项目初始化及运行**

    ### 安装步骤
    1.  **克隆项目**

        ```bash
        git clone <repository-url>
        cd LaravelApp
        ```

    2.  **配置环境**

        ```bash
        cp .env.example .env
        php artisan key:generate
        ```

    3.  **安装依赖**

        ```bash
        composer install
        npm install # or pnpm install
        ```

    4.  **数据库设置**

        ```bash
        # 创建 SQLite 数据库文件
        touch database/database.sqlite

        # 运行迁移和填充数据
        php artisan migrate --seed
        ```

    5.  **构建前端资源**

        ```bash
        # 开发环境（监听文件变化）
        npm run dev

        # 生产环境构建
        npm run home:build
        npm run admin:build
        ```

    6.  **启动服务**
        ```bash
        php artisan serve
        ```

    ### 开发命令

    ```bash
    # 格式化代码
    npm run format

    # 代码检查
    npm run lint:check

    # 构建分析
    npm run home:analyze
    npm run admin:analyze
    ```

- **后台管理入口**

    ### 登录地址
    - 默认访问地址：`/manager`
    - 可在 [routes/admin.php](routes/admin.php) 中修改路由前缀

    ### 默认账户
    - 管理员账户在数据库种子中创建
    - 可通过 `AuthSeeder.php` 查看默认登录信息

- **权限管理系统**

    ### 权限配置
    - **管理地址**：`/manager/permissions`
    - **数据种子**：[database/seeders/AuthSeeder.php](database/seeders/AuthSeeder.php)
    - **菜单显示**：需要配置 `admin_menu` 表中的相关记录

    ### 权限特性
    - 基于角色的访问控制（RBAC）
    - 细粒度的权限管理
    - 菜单权限控制
    - 资源级别权限

    ### 快速权限设置
    1.  在后台访问 `/manager/permissions`
    2.  创建新权限或编辑现有权限
    3.  在角色管理中分配权限
    4.  在菜单管理中配置显示

- **自定义表单系统**

    ### 架构概览
    - **核心位置**：`App\Services\Form`
    - **表单目录**：`App\Forms`
    - **基类**：`App\Services\Form\FormBuilder`

    ### 表单特性
    - 🎨 丰富的表单组件库
    - 🔧 链式调用 API
    - 📱 响应式布局
    - ✅ 内置验证支持
    - 🎭 支持关联模型 - `scheme()` 表单构建, 示例：
      ```php
      use MetaFormTrait;

                protected static function schema(Form $form): void
                {
                    $categories = Category::whereNotIn('id', array_filter([$form->data['id'] ?? null]))
                        ->pluck('id', 'name')
                        ->all();

                    $form->add(Input::make('name', 'Name'))
                        ->add(Select::make('parent_id', 'Parent')->options($categories))
                        ->add(Input::make('directory', 'Path'))
                        ->add(Toggle::make('show', 'Show In Nav')->defaultValue(true))
                        ->add(
                            Select::make('type', 'Type')
                                ->options(CategoryType::options())
                                ->defaultValue(CategoryType::View->value)
                        )
                        ->add(Input::make('rank', 'Rank')->number()->defaultValue(0))
                        ->add(self::metaBlock());
                }

                ```

            - `hydrate()` 可选，表单数据填充，常用于关联模型的数据补充，默认 `$this->data` `$this->data->toArray()`, 示例：
                ```php
                protected static function hydrate(Model|Category $model): array
                {
                    return [
                        ...$model->toArray(),
                        ...$model->meta->toArray(),
                    ];
                }

                 ```

        - traits
            - `MetaFormTrait`: `meta` 关联模型的表单生成器
            - `HydateMetaTrait`: `meta` 关联模型的数据填充
            - `SlugFormTrait`: `slug` 关联模型的表单生成器
            - `HydrateSlugTrait`: `slug` 关联模型的数据填充
            - `TagFormTrait`: `tag` 关联模型的表单生成器
            - `HydrateTagTrait`: `tag` 关联模型的数据填充

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
            - `maxLength(int $length)` 多选时(->multiple())最大可选数量限制
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
    1.  生成迁移、模型、控制器、请求和策略

        ```bash
        php artisan make:model Post -m
        php artisan make:controller Admin/PostController --resource
        php artisan make:request Admin/PostRequest
        php artisan make:policy PostPolicy --model=Post
        ```

    2.  在 `routes/admin.php` 中注册资源路由（或在控制器中按现有风格追加）

    3.  在权限表中创建对应的权限记录（`permissions`）并在 `menu`/`admin menu` 配置中添加菜单项，方便在后台导航展示。

    4.  在相应的视图中使用 `App\Services\Form` 的表单组件来构建表单，以及使用项目的 `datatable` 组件（位于 `App\Services`）来渲染列表页面。

    5.  在策略（Policy）中实现授权，并在 `PermissionController` 中将新权限分配给角色。

--

## 前端

- **Slug renderer 概述**
    - 项目通过 `Slug` 模型与一组“渲染器（renderers）”来处理基于 slug 的页面路由。主要组件位于 [app/Http/Renderers](app/Http/Renderers)：
        - `SlugRenderManager`：负责遍历已注册的 `SlugRenderer`，找出能处理当前 `sluggable` 实例的渲染器并返回渲染结果。
        - `CategoryRenderer`, `PostRenderer`, `TagRenderer`：各模型对应的渲染器实现。
        - `CategoryRenderer` 内部进一步委托 `CategorySubRenderer`（例如 `CategoryPostsRenderer`, `CategoryViewRenderer`）来按 `Category::type` 选择具体渲染方式。

    - 路由请见 [routes/web.php](routes/web.php)，`{slug}` 路由映射到 `SlugController`（或 `Home\SlugController`），并通过 `SlugRenderManager` 完成最终视图返回。

- **如何添加资源对应的 renderer（步骤）**
    1. Model 需要 use `App\Models\Traits\Sluggable`

    2. 新建渲染器类，实现 `App\Http\Renderers\Contracts\SlugRenderer`（或 `CategorySubRenderer` 用于分类子渲染器）。示例骨架：

        ```php
        <?php
        namespace App\Http\Renderers;

        use App\Http\Renderers\Contracts\SlugRenderer;
        use Illuminate\Http\Response;

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

    3. 在 `app/Providers/SlugServiceProvider.php` 中注册新的渲染器标签（示例已存在）：

        ```php
        $this->app->tag([
        		 CategoryRenderer::class,
        		 PostRenderer::class,
        		 TagRenderer::class,
        		 MyResourceRenderer::class, // 新增
        ], SlugRenderer::class);
        ```

    4. 如为 `Category` 的新类型（`CategoryType`），在 `CategoryRenderer` 的子渲染器集合中注册对应的 `CategorySubRenderer`，并确保其 `supports()` 实现能按 `->type` 正确匹配。

- **`App\Services\Seo` 简介**
    - `App\Services\Seo` 为集中化的 SEO 元数据管理器，支持：
        - 以模型为来源生成 meta（模型需使用 `App\Models\Traits\Metable`）
        - 手动设置 title/keywords/description/url/image
        - 将 SEO 数据共享到视图层，最终通过 `home::seo` 视图片段渲染

    - 常见用法示例：在渲染器中：

        ```php
        // model has meta
        App\Facades\Seo::model($model);

        // or custom
        App\Facades\Seo::seo('Title', description: '...');
        ```

---

## 开发指南

### 代码规范

- 使用 Laravel Pint 进行代码格式化：`./vendor/bin/pint`
- 遵循 PSR-12 编码标准
- 前端使用 ESLint 和 Prettier

### 测试

```bash
# 运行所有测试
php artisan test

# 运行特定测试
php artisan test --filter Feature/UserTest
```

### 调试

- 启用 DebugBar：`APP_DEBUG=true`
- 查询日志：`DB::enableQueryLog()`
- 日志查看：`tail -f storage/logs/laravel.log`

---

## 部署说明

### 生产环境配置

1. **环境变量**

    ```bash
    APP_ENV=production
    APP_DEBUG=false
    ```

2. **优化**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize
    ```

3. **文件权限**
    ```bash
    chmod -R 755 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
    ```

### Docker 部署

项目支持 Docker 部署，可创建 `docker-compose.yml` 配置文件。

---

_最后更新：2026年1月_
