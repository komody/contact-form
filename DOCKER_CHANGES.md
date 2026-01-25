# Docker設定変更報告

## 変更日
2026年1月24日

## 変更内容

### 1. `docker-compose.yml` の `version` 属性を削除

**変更前:**
```yaml
version: '3.8'

services:
  ...
```

**変更後:**
```yaml
services:
  ...
```

**理由:**
- Docker Composeの新しいバージョンでは`version`属性が廃止予定となり、警告が表示されていたため
- 最新のDocker Composeでは`version`属性は不要

### 2. 各サービスに `platform: linux/amd64` を追加

**変更内容:**
以下の各サービスに`platform: linux/amd64`を追加しました：
- `nginx`
- `php`
- `mysql`
- `phpmyadmin`

**変更例:**
```yaml
nginx:
  image: nginx:1.21.1
  platform: linux/amd64  # 追加
  ports:
    - "80:80"
  ...
```

**理由:**
- Apple Silicon Mac（ARM64アーキテクチャ）で実行する際に、以下のエラーが発生していたため：
  ```
  no matching manifest for linux/arm64/v8 in the manifest list entries
  ```
- `platform: linux/amd64`を指定することで、Rosetta 2を使用してx86_64イメージを実行可能に

## 影響範囲

- **互換性**: 変更により、Apple Silicon Macでも正常に動作するようになりました
- **パフォーマンス**: x86_64イメージを実行するため、ネイティブARM64イメージと比較して若干のパフォーマンス低下の可能性がありますが、実用上問題ありません
- **既存環境**: Intel MacやLinux環境での動作に影響はありません

## 動作確認

以下のコマンドで正常に起動することを確認済み：
```bash
docker compose up -d --build
```

## 備考

- コマンドは `docker-compose`（ハイフンあり）ではなく、`docker compose`（ハイフンなし）を使用してください
- 最新のDocker Desktopでは`docker compose`が推奨されています
