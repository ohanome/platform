package database

import (
	"database/sql"
	"fmt"
	"log"
	"ohano/forklift/env"
	"ohano/forklift/errors"
	"ohano/forklift/network"
	"os"
	"time"
)

var mysqlHostKey = "MYSQL_HOSTNAME"
var mysqlPasswordKey = "MYSQL_PASSWORD"
var mysqlUsernameKey = "MYSQL_USER"
var mysqlDatabaseKey = "MYSQL_DATABASE"
var mysqlPortKey = "MYSQL_PORT"
var mysqlHost = ""
var mysqlPassword = ""
var mysqlUsername = ""
var mysqlDatabase = ""
var mysqlPort = ""

func init() {
	env.LoadEnv()
	SetConnectionParameters()
}

func SetConnectionParameters() {
	mysqlHost = os.Getenv(mysqlHostKey)
	mysqlPassword = os.Getenv(mysqlPasswordKey)
	mysqlUsername = os.Getenv(mysqlUsernameKey)
	mysqlDatabase = os.Getenv(mysqlDatabaseKey)
	mysqlPort = os.Getenv(mysqlPortKey)

	if mysqlHost == "database" {
		mysqlHost = "192.168.10.10"
		mysqlPassword = "secret"
		mysqlUsername = "homestead"
		mysqlDatabase = "ohano"
		mysqlPort = "3306"
	}
}

func CheckConnection() {
	if !network.PortIsOpen(mysqlHost, mysqlPort) {
		fmt.Printf("Port '%s' on host '%s' is closed.\n", mysqlPort, mysqlHost)
		os.Exit(1)
	}
}

func CreateConnection() *sql.DB {
	CheckConnection()
	if mysqlHost == "" {
		SetConnectionParameters()
	}

	db, err := sql.Open("mysql", mysqlUsername+":"+mysqlPassword+"@tcp("+mysqlHost+":"+mysqlPort+")/"+mysqlDatabase)
	errors.Catch(err)
	db.SetConnMaxLifetime(time.Minute * 3)
	db.SetMaxOpenConns(10)
	db.SetMaxIdleConns(10)

	return db
}

func Select(query string) *sql.Rows {
	db := CreateConnection()
	defer func(db *sql.DB) {
		err := db.Close()
		errors.Catch(err)
	}(db)

	log.Println("About to execute query '" + query + "'")

	rows, dbErr := db.Query(query)
	errors.Catch(dbErr)

	return rows
}

func SelectWhere(query string, params []string) *sql.Rows {
	db := CreateConnection()
	defer func(db *sql.DB) {
		err := db.Close()
		errors.Catch(err)
	}(db)

	fmt.Println(params)
	log.Println("About to execute query '" + query + "'")

	rows, dbErr := db.Query(query, params)
	errors.Catch(dbErr)

	return rows
}
