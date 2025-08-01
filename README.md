[English](./README.md) | [中文](./README.zh-CN.md)

# AI Post Creator for WordPress

[![License: GPL v2 or later](https://img.shields.io/badge/License-GPL%20v2%20or%20later-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/ai-post-creator?label=Stable%20Tag)](https://wordpress.org/plugins/ai-post-creator/)

A simple but powerful WordPress plugin that provides a REST API endpoint to create Gutenberg posts from AI-generated (or any) Markdown content.

This plugin is the perfect bridge between modern content workflows and WordPress, ensuring programmatically created posts are perfectly compatible with the Gutenberg block editor.

## ✨ Features

- **Markdown to Gutenberg**: Converts standard Markdown into a clean Gutenberg block structure.
- **Custom REST API Endpoint**: Provides a dedicated, easy-to-use API endpoint for content creation.
- **AI-Ready**: Designed specifically for content generated by AI models like Gemini, GPT, etc.
- **Developer-Friendly**: Secure and easy to integrate into any application or workflow.
- **Lightweight**: No bloat, just one core function done well.

## 🚀 Why Use This Plugin?

Manually copying and pasting AI-generated content into WordPress is slow and error-prone. This plugin automates the process, allowing you to:

- **Automate Content Pipelines**: Directly publish from your scripts or applications.
- **Ensure Compatibility**: Avoids HTML formatting issues by using WordPress's native block conversion functions.
- **Integrate with Headless Setups**: Use WordPress as a backend and feed it content from anywhere.

## 📦 Installation

1.  **Download `Parsedown.php`**: This plugin requires the `Parsedown.php` library.
    -   Go to [parsedown.org](https://parsedown.org/).
    -   Click the "Download" button to get the single `Parsedown.php` file.

2.  **Add `Parsedown.php` to the Plugin**:
    -   Place the downloaded `Parsedown.php` file into the root of this plugin's directory (`/wp-content/plugins/ai-post-creator/`).

3.  **Install the Plugin**:
    -   Upload the entire `ai-post-creator` folder to the `/wp-content/plugins/` directory on your WordPress site.
    -   Activate the "AI Post Creator" plugin through the 'Plugins' menu in WordPress.

## 🔧 API Usage

To create a post, send a `POST` request to the following REST API endpoint. You must be authenticated with the WordPress REST API (e.g., using Application Passwords) and have the `publish_posts` capability.

**Endpoint URL:**
`https://your-domain.com/wp-json/ai-creator/v1/create-post`

**Request Body (JSON):**

```json
{
  "title": "Your Awesome AI-Generated Post Title",
  "markdown_content": "## A Heading\n\nThis is a paragraph with **bold** text and a [link](https://wordpress.org).\n\n- List item 1\n- List item 2",
  "status": "publish"
}
```

-   `title` (string, **required**): The title of the post.
-   `markdown_content` (string, **required**): The full Markdown content for the post body.
-   `status` (string, *optional*): The post status. Can be `publish`, `draft`, or `pending`. Defaults to `draft`.

**Success Response (`201 Created`):**

```json
{
    "success": true,
    "post_id": 123,
    "post_url": "https://your-domain.com/your-awesome-post-title/",
    "message": "Post created successfully."
}
```

## 📝 License

This plugin is licensed under the GPLv2 or later.
