package drupal

import (
	"database/sql"
	"ohano/forklift/database"
	"ohano/forklift/errors"
)

func init() {
}

func GetActiveSessions() []string {
	query := "SELECT uid FROM sessions"
	rows := database.Select(query)

	var uids []string
	for rows.Next() {
		var uid string
		err := rows.Scan(&uid)
		errors.Catch(err)
		uids = append(uids, uid)
	}

	defer func(rows *sql.Rows) {
		err := rows.Close()
		errors.Catch(err)
	}(rows)

	return uids
}

func GetActiveSessionByUid(uid string) []string {
	query := "SELECT uid FROM sessions WHERE uid = ?"
	rows := database.SelectWhere(query, []string{uid})

	var uids []string
	for rows.Next() {
		var uid string
		err := rows.Scan(&uid)
		errors.Catch(err)
		uids = append(uids, uid)
	}

	defer func(rows *sql.Rows) {
		err := rows.Close()
		errors.Catch(err)
	}(rows)

	return uids
}
