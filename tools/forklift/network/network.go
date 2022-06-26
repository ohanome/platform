package network

import (
	"fmt"
	"net"
	"ohano/forklift/errors"
	"time"
)

func PortIsOpen(host string, port string) bool {
	timeout := time.Second
	conn, err := net.DialTimeout("tcp", net.JoinHostPort(host, port), timeout)
	if err != nil {
		fmt.Println(err)
		return false
	}

	if conn != nil {
		cErr := conn.Close()
		errors.Catch(cErr)
		return true
	}

	return false
}
