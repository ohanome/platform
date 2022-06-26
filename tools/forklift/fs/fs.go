package fs

import (
	"io/ioutil"
	"ohano/forklift/errors"
	"os"
)

func CreateDirIfNotExists(dirname string, perm os.FileMode) {
	if !DirectoryExists(dirname) {
		err := os.Mkdir(dirname, perm)
		errors.Catch(err)
	}
}

func DeleteDir(dirname string) {
	if DirectoryExists(dirname) {
		err := os.RemoveAll(dirname)
		errors.Catch(err)
	}
}

func CopyFile(sourceFile, destinationFile string, perm os.FileMode) {
	input, err := ioutil.ReadFile(sourceFile)
	errors.Catch(err)

	err = ioutil.WriteFile(destinationFile, input, perm)
	errors.Catch(err)
}

func FileExists(filename string) bool {
	_, err := os.Stat(filename)
	if os.IsNotExist(err) {
		return false
	} else if err != nil {
		errors.Catch(err)
	}

	return true
}

func DirectoryExists(dirname string) bool {
	return FileExists(dirname)
}
