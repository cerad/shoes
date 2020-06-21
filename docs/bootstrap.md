Started by updating yarn
```
sudo apt-get update
sudo apt-get install yarn # if not already installed
curl --compressed -o- -L https://yarnpkg.com/install.sh | bash
yarn self-update # tried this but id needs a package.json file
```
Then update/install nvm and nodejs
```
sudo apt install build-essential checkinstall libssl-dev
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.35.1/install.sh | bash
# Close and reopen the terminal.
# Kind of switched to npm and ran a bunch of commands
# ended up with node version v8.17.0
nvm install v12.18.1 # used this to actually update node
```

Following the Symfony docs
```
composer require symfony/webpack-encore-bundle # must be first !!!
yarn add bootstrap --dev
yarn add jquery popper.js --dev
yarn install # seemed to work okay
yarn encore dev --watch
yarn encore production
```
