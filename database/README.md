# Expresshipping database bridge

The authenticated `db_bridge.php` endpoint connects to the existing MySQL connection file outside `public_html`. Never commit `Connect.php`, database credentials, or the bridge token.

## Supported actions

All requests must use HTTPS, POST, JSON, and an Authorization Bearer token.

- `ping`: verify that the bridge and MySQL connection are available.
- `tables`: list database tables.
- `describe`: inspect one table's columns.
- `select`: run a read-only SELECT query (maximum 100 returned rows).
- `backup_page`: save a complete `pages` row snapshot.
- `update_page`: update selected fields in one `pages` row only.

## Page backups and updates

Backups are written to `../db_bridge_backups/` relative to the web root, which places them outside `public_html` when the endpoint is installed in `public_html`. The directory is created with mode 0700 and backup files with mode 0600.

Every `update_page` request:
1. Starts a database transaction and locks the target row.
2. Writes a complete pre-update JSON backup.
3. Updates only the supplied allowlisted fields for that page ID.
4. Commits the transaction and returns the backup filename.

The update endpoint does not accept arbitrary SQL, table names, IDs in the fields object, or changes to tables other than `pages`. Allowed fields are defined in `db_bridge.php`.

### Create a backup

```json
{"action":"backup_page","id":12}
```

### Update a page

```json
{
  "action": "update_page",
  "id": 12,
  "fields": {
    "Page_Contents": "<section>Updated template</section>",
    "styles_css": ".page { max-width: 1200px; }",
    "script_js": "console.log('template ready');"
  }
}
```

The example ID and content are illustrative only. Confirm the target record and review its current contents before sending an update.

## Operational notes

- Deploy the updated `db_bridge.php` to the web root before using the new actions.
- Confirm the hosting account's PHP version supports mysqli and `mysqli_stmt::get_result()` (normally provided by mysqlnd).
- Test with `ping`, then `backup_page` on a known page ID before attempting any update.
- Keep the bridge token private and rotate it if it is ever exposed.
- Do not expose the backup directory through a public URL.
