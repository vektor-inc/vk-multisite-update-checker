# VK Multisite Update Checker

マルチサイトのネットワーク管理画面で VK 製品（テーマ・プラグイン）の更新通知を受け取れるようにする軽量プラグインです。

## 背景

WordPress マルチサイトのネットワーク管理画面はメインサイト（blog_id=1）のコンテキストで動作します。そのため、メインサイトで有効でないテーマ・プラグインの PUC（Plugin Update Checker）が初期化されず、更新通知が表示されません。

この挙動はサブディレクトリ型・サブドメイン型に関わらず共通です。

### 更新通知が届く条件（本プラグインなしの場合）

| 種類 | 条件 |
|---|---|
| プラグイン | メインサイトで有効化、またはサイトネットワークで有効化 |
| テーマ | メインサイトの有効テーマであること |

本プラグインをサイトネットワークで有効化することで、上記の条件を満たさない場合でも更新通知を受け取れるようになります。

## インストール

1. [Releases](https://github.com/vektor-inc/vk-multisite-update-checker/releases) から ZIP をダウンロード
2. WordPress のネットワーク管理画面 > プラグイン > 新規追加 > プラグインのアップロード からインストール
3. **サイトネットワークで有効化**

## リリース

タグを push すると GitHub Actions が自動で ZIP を作成し GitHub Release を発行します。

```bash
git tag 0.7.0
git push origin 0.7.0
```

## 対応製品

### テーマ

- Lightning Pro
- Katawara
- Smaveksive

### プラグイン

- Lightning G3 Pro Unit
- VK Blocks Pro
- Lightning G3 Expand Widget Areas
- Lightning G3 Evergreen
- Lightning G3 Vekuan
- Lightning Skin Charm
- Lightning Skin Fort
- Lightning Skin Pale
- Lightning Skin Variety
- Lightning Skin JPN Style
- VK AB Testing
- VK Add Fonts for Block Editor
- VK Filter Search Pro
- VK FullSite Installer
- VK Video Block Pro
- Lightning Video Unit
- VK AI EditMate

## 仕組み

1. 対応製品がインストールされているかファイルの存在で確認
2. その製品が既に有効（メインサイトまたはネットワークで有効化済み）ならスキップ（重複防止）
3. 有効でない場合のみ PUC を初期化して更新通知を提供
4. ライセンスキーが必要な製品は、現在のサイトにキーがなければ他のサイトから自動で探索

## ライセンス

GPL-2.0-or-later
