[English](./README.md) | [中文](./README.zh-CN.md)

# AI-Post-Creator：为自动化而生的 WordPress 内容发布插件

## 前言：为什么你需要一个内容发布插件？

在自动化和 AI 驱动的内容时代，我们经常需要以编程方式在 WordPress 网站上创建文章。无论是对AI生成的内容进行归档，还是将其他系统中的文章同步到博客，我们都需要一个可靠的接口。

然而，直接使用 WordPress 内置的 REST API 来创建含有复杂格式（如列表、代码块、引用）的文章，往往会遇到困难。Gutenberg（古腾堡）区块编辑器的复杂性，在 API 环境下很难被完美模拟。

为了解决这个痛点，我开发了 **AI-Post-Creator**——一个轻量级、专为自动化而生的 WordPress 插件。它的核心使命只有一个：**提供一个极其简单、稳定、可靠的 API 端点，用于接收 Markdown 内容并将其无缝发布为 WordPress 文章。**

## 核心功能

-   **Markdown 优先**：将 Markdown 作为内容输入的“一等公民”，你无需在客户端处理复杂的 HTML 转换。
-   **专用的 REST API 端点**：提供一个 `/wp-json/ai-creator/v1/create-post` 端点，专为创建文章而设计，逻辑清晰。
-   **服务器端转换**：插件在 WordPress 服务器端，使用强大的 `Parsedown` 库将 Markdown 安全地转换为干净的 HTML，确保了最佳的兼容性。
-   **兼容 Gutenberg**：插件生成的 HTML 内容会被 Gutenberg 编辑器自动识别并放入“经典”或“HTML”区块中，既保证了前端的正确显示，也方便了后台的二次编辑。
-   **轻量且安全**：插件代码极简，无任何不必要的依赖。它使用 WordPress 内置的“应用程序密码”进行认证，并检查用户是否拥有 `publish_posts` 权限，确保了操作的安全性。

## 安装指南

安装过程非常简单，只需两步：

### 1. 下载依赖库 `Parsedown.php`

本插件依赖于 `Parsedown` 这个优秀的 PHP 库来处理 Markdown 转换。
-   请访问 [Parsedown 官网](https://parsedown.org/)。
-   点击 **Download** 按钮，获取 `Parsedown.php` 这个单文件。

### 2. 安装插件

1.  将下载好的 `Parsedown.php` 文件，放入 `ai-post-creator` 插件的根目录中。
2.  将整个 `ai-post-creator` 文件夹上传到你 WordPress 网站的 `/wp-content/plugins/` 目录下。
3.  登录 WordPress 后台，在“插件”菜单中，找到并激活“AI Post Creator”。

安装完成！插件的 API 端点现在已经可用。

## API 使用文档

这是将你的自动化脚本与 WordPress 连接起来的关键。

-   **端点 URL:** `https://your-domain.com/wp-json/ai-creator/v1/create-post`
-   **请求方法:** `POST`
-   **认证方式:** HTTP Basic Authentication。请使用你的 WordPress 用户名和一个**应用程序密码**。你可以在 WordPress 后台的“用户”->“个人资料”页面找到并生成应用程序密码。

### 请求体 (Request Body)

你需要发送一个 JSON 对象，包含以下字段：

-   `title` (string, **必需**): 文章的标题。
-   `markdown_content` (string, **必需**): 完整的 Markdown 格式的文章正文。
-   `status` (string, *可选*): 文章状态。可以是 `publish`（直接发布）、`draft`（保存为草稿）或 `pending`（待审）。默认为 `draft`。

**请求示例 (JSON):**
```json
{
  "title": "我的第一篇自动化发布的文章",
  "markdown_content": "## 这是一个二级标题\n\n这是段落内容，包含 **加粗** 和 *斜体*。\n\n- 列表项 1\n- 列表项 2\n\n```php\necho 'Hello, World!';\n```",
  "status": "publish"
}
```

### 成功响应 (Success Response)

如果文章创建成功，你将收到一个 `201 Created` 状态码和包含新文章信息的 JSON 对象：

```json
{
    "success": true,
    "post_id": 456,
    "post_url": "https://your-domain.com/my-first-automated-post/",
    "message": "Post created successfully."
}
```

### `curl` 命令行示例

You可以使用 `curl` 快速测试 API 功能：

```bash
curl -X POST "https://your-domain.com/wp-json/ai-creator/v1/create-post" \
     -u "your_username:xxxx xxxx xxxx xxxx xxxx xxxx" \
     -H "Content-Type: application/json" \
     -d '{
           "title": "通过 Curl 发布的文章",
           "markdown_content": "# 测试\n\n这非常方便！",
           "status": "publish"
         }'
```
**注意：** 请将 `your_username` 和应用程序密码替换为你自己的凭据。

## 结论

AI-Post-Creator 是连接你的自动化内容管道和 WordPress 网站的完美桥梁。它通过一个简单、稳健的 API，将复杂的发布流程抽象化，让你能专注于业务逻辑，而不是处理繁琐的 WordPress 内部机制。

立即将它集成到你的工作流中，体验自动化发布的乐趣吧！

## 贡献

欢迎各种形式的贡献！无论是提交 bug 报告、功能请求，还是直接贡献代码，我们都非常欢迎。

## 许可

本插件采用 GPLv2 或更高版本的许可证。
