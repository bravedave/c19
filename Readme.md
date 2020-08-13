## C19 - Simple Vistation Tracking

With Covid19 it's necessary to record who is attending events.

#### Install
1. Clone into a Folder
```bash
umask 022
git clone https://github.com/bravedave/c19.git c19
```

2. This part will either install or update the app
```
cd c19
umask 022
git pull
git fetch -p
umask 002
composer u
```

3. Now - the previous example would have generated an error, _DBNotConfigured_
   1. mv application/data/defaults-sample.json application/data/defaults.json
   2. review application/data/defaults.json and adjust for your requirements
   3. re-run update ```composer u```

4. Check the ownership on the data folder reflects access from the webserver
   * chmod -R g+w,o+w application/data/*
