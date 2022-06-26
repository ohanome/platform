package env

import (
	"fmt"
	"github.com/joho/godotenv"
	"ohano/forklift/errors"
	"ohano/forklift/fs"
	"os"
)

var envFile = "../../.env"

func init() {
	LoadEnv()
}

func LoadEnv() {
	if !fs.FileExists(envFile) {
		fmt.Println("Could not find .env file")
		os.Exit(1)
	}

	if os.Getenv("ENV_LOADED") == "1" {
		return
	}

	err := godotenv.Load(envFile)
	errors.Catch(err)
	err = os.Setenv("ENV_LOADED", "1")
	errors.Catch(err)
}
