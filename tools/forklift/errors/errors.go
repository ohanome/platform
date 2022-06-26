package errors

import (
	"fmt"
	"os"
)

func Catch(err error) {
	if err != nil {
		fmt.Println(err)
		os.Exit(1)
	}
}
