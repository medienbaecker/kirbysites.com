# [Kirbysites](https://kirbysites.com)

A showcase of websites built with [Kirby](https://getkirby.com).

## Your submission

Submit your own site by opening a pull request!

1. [Fork this repo](https://github.com/medienbaecker/kirbysites.com/fork)
2. Add your site to `content/`
3. Open a pull request

### Folder structure

```
content/YYYYMMDD_your-domain/
├── website.md
├── frontend.webp
├── frontend.webp.md
├── 1-panel-dashboard.webp
├── 1-panel-dashboard.webp.md
├── 2-panel-page.webp
├── 2-panel-page.webp.md
└── ...
```

### Content file (website.md)

```
Title: Example

----

Url: https://example.com

----

Text: by (link: https://example.com text: designer) and (link: https://example.com text: developer) with (link: https://example.com text: nice fonts)

----

Date: 2026-01-01 14:25:19
```

### Image meta data files (frontend.webp.md, 1-panel-dashboard.webp.md, etc.)

```
Alt: Description of the screenshot for accessibility

----

Caption: Explain what's interesting about this. Link to (link: https://plugins.getkirby.com text: plugins) you used.
```

## CLI Commands

Requires the [Kirby CLI](https://github.com/getkirby/cli). For the screenshots you'll have to run `npm install` in the `site/plugins/screenshot` folder first.

| Command | Description |
|---------|-------------|
| `kirby new` | Create a new website page with frontend screenshot |
| `kirby screenshot` | Take a screenshot of an existing page |
| `kirby og` | Take a screenshot of `http://kirbysites.test` for the OG image |
| `kirby check-panels` | Check if panel URLs are (still) accessible |

## License

MIT — but note that [Kirby](https://getkirby.com) requires a [license](https://getkirby.com/license) for public websites, and submitted content belongs to its respective owners.
