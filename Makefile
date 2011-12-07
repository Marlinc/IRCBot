default: runtests

tests: 
	echo aa
	return 1
	
runtests:
	phpunit -v --debug tests/
