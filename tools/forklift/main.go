package main

import (
	"fmt"
	_ "github.com/go-sql-driver/mysql"
	"math/rand"
	"ohano/forklift/drupal"
	"os"
	"strings"
	"time"
)

func Rand(min int, max int) time.Duration {
	return time.Duration(rand.Intn(max-min)+min) * time.Millisecond
}

//goland:noinspection ALL
func SampleProgressbar() {
	const col = 30
	// Clear the screen by printing \x0c.
	bar := fmt.Sprintf("\r[%%-%vs]", col)
	for i := 0; i < col; i++ {
		r := Rand(1, 200)
		fmt.Printf(bar, strings.Repeat("=", i)+">")
		time.Sleep(r)
	}
	fmt.Printf(bar+" Done!\n", strings.Repeat("=", col))
}

func main() {
	if len(os.Args) > 1 {
		for _, arg := range os.Args[1:] {
			switch arg {
			case "test":

				uids := drupal.GetUserIds()
				fmt.Println("drupal.GetUserIds")
				fmt.Println(uids)

				uids = drupal.GetActiveSessions()
				fmt.Println("drupal.GetActiveSessions")
				fmt.Println(uids)

				uids = drupal.GetActiveSessionByUid("1")
				fmt.Println("drupal.GetActiveSessionByUid")
				fmt.Println(uids)

				os.Exit(0)

			case "usage":
			case "help":
				Usage()
				os.Exit(0)

			default:
				fmt.Println("Unknown argument(s)")
				os.Exit(1)
			}
		}
	} else {
		Usage()
		os.Exit(0)
	}
}
