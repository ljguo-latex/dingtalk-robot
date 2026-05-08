# DingTalk Robot SDK

钉钉自定义机器人 PHP SDK，支持全部消息类型和加签安全模式。

## 安装

```bash
composer require your-name/dingtalk-robot
```

## 快速开始

```php
use DingTalk\Robot;

$robot = new Robot('ACCESS_TOKEN', 'SECRET'); // SECRET 可省略（不启用加签）
```

## 消息类型

### 文本消息

```php
$robot->text('服务器 CPU 超过 90%')
      ->at(['138xxxxxxxx'])   // @指定手机号
      ->send();

$robot->text('紧急通知')->atAll()->send(); // @全体
```

### Markdown 消息

```php
$robot->markdown('上线通知', "## v1.2.0 已上线\n- 修复登录 bug\n- 新增导出功能")
      ->at(['138xxxxxxxx'])
      ->send();
```

### Link 消息

```php
$robot->link('每日周报', '本周精选文章，点击查看', 'https://example.com/weekly', 'https://example.com/cover.jpg')
      ->send();
```

### ActionCard — 单按钮

```php
$robot->actionCard('请假审批', '张三申请 2026-05-08 ~ 05-10 请假')
      ->single('去审批', 'https://example.com/approve/123')
      ->send();
```

### ActionCard — 多按钮

```php
$robot->actionCard('代码审查', '## PR #88\n新增支付模块')
      ->addButton('合并', 'https://example.com/pr/88/merge')
      ->addButton('拒绝', 'https://example.com/pr/88/reject')
      ->horizontal()  // 横排，默认竖排
      ->send();
```

### FeedCard — 多图文

```php
$robot->feedCard()
      ->addLink('Go 1.22 新特性', 'https://example.com/go122', 'https://example.com/go.png')
      ->addLink('PHP 8.4 发布',   'https://example.com/php84', 'https://example.com/php.png')
      ->send();
```

## 获取 Token 和 Secret

钉钉群 → 群设置 → 智能群助手 → 添加机器人 → 自定义机器人  
安全设置选「**加签**」，复制 Webhook URL 中的 `access_token` 和显示的 `Secret`（以 `SEC` 开头）。

## 错误处理

```php
use DingTalk\Exception\DingTalkException;

try {
    $robot->text('Hello')->send();
} catch (DingTalkException $e) {
    echo $e->getMessage();
}
```

## 环境要求

- PHP >= 8.0
- ext-curl
- ext-json

## License

MIT
