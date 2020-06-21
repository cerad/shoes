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
```

Following the Symfony docs
```
yarn add bootstrap --dev
yarn add jquery popper.js --dev
composer require symfony/webpack-encore-bundle
yarn install # seemed to work okay
```
