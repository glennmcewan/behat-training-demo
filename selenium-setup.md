# Overview
This is a very quick guide to demonstrate how to integrate Selenium Grid with Behat.
This article will demonstrate which dependencies are required, how to install Selenium, and how to run Selenium on your projects.

# Assumptions
This guide assumes that you are running these tests on your local development machine, which will contain a unix-based VM. In this guide, I will be showing you how to run the Selenium Grid inside of the virtual machine, and register a Selenium Node on the **host** machine (the node is responsible for spawning a browser and controlling it).

There is an assumption that you already have a Behat project up and running, and that you just want to hook up Selenium to enhance the testability.

The final assumption is that you have Java and Composer installed, as this guide will not cover how to install them.

# Prerequisites
To install Selenium, you will need the following dependencies:
- [Selenium Standalone Server](http://selenium-release.storage.googleapis.com/2.53/selenium-server-standalone-2.53.1.jar)
- - This packages acts as both the **hub** and **node** (more on that later)
- A web driver -- in this case, we will be using [Chromedriver](http://chromedriver.googlecode.com/files/chromedriver_linux32_23.0.1240.0.zip)
- Java - you must have Java installed on both the host and guest machines (Selenium depends on Java)
- [Composer](https://getcomposer.org/) 
- [Behat](http://behat.org)
- [Mink](http://mink.behat.org)
- [Mink Selenium2Driver](https://github.com/minkphp/MinkSelenium2Driver)

# Selenium Installation
1. **Download** Selenium Standalone Server and Chromedriver on to your host machine
2. **Move** them to an executable location, and make sure the file permissions allow execution
3. **Copy** Selenium Standalone Server on to your guest machine in an executable path

Selenium is now _installed_. Selenium does not actually need _installing_, but rather placed in a sensible location on both the host and guest machines.
The web driver only needs to exist on the host machine (where the node is configured).

# Selenium Usage
Firstly, launch an instance of the Selenium Standalone Server inside the Guest Machine with the following parameters:
```
java -jar /path/to/selenium-server-standalone.jar -role hub
```

Of course, replace the path to Selenium with the real location in which you placed Selenium.
Now, Selenium Grid is running inside of the Guest Machine, this serves as the hub for all selenium tasks. This will allow multiple nodes to process Selenium jobs (think of these nodes as workers, which allows for distributed and concurrent UI test automation).

**Note**: Java JAR files must be passed absolutely; relative paths or executables in the `$PATH` will not work.

And now, you will need to register a Selenium Node with the hub, and this will run on the Host Machine, so that the browser can be run in a graphical environment:
```
java -jar /path/to/selenium-server-standalone.jar -role node -hub http://{IP_OF_VM}:4444/grid/register -Dwebdriver.chrome.driver="/path/to/chromedriver.exe" -browser "browserName=chrome,version=ANY,maxInstances=5,platform=WINDOWS"
```
**Note**: the above line should not be copied and pasted, as it requires some tweaking for your environment.
You will need to replace the following parameters in the above statement for it to work:
- Path to Selenium Standalone Server
- IP of the guest machine / VM (where Selenium Grid is running)
- Path to the webdriver (Chromedriver)
- Platform, as you may not be running Windows

Selenium is now ready to accept jobs. A central hub (grid) is running on the guest machine / VM, and you have registered a node on the host machine which is considered to be the 'worker' that will spawn a browser and execute your tests against.
# Behat and Selenium Integration
Now that Selenium is up and running, you're ready to integrate Behat with Selenium.

In your Behat project, make sure you have pulled in Mink and Mink's Selenium2Driver with the following command:
```
composer require --dev behat/mink behat/mink-extension behat/mink-selenium2-driver
```

Now you have the dependencies, you will need to register them with Behat. Add the following lines to the relevant **profile** (typically the default one) in your `behat.yml` file:
```
extensions:
    Behat\MinkExtension:
        base_url: 'http://localhost:8000'
            browser_name: 'chrome'
            sessions:
                default:
                    selenium2:
                        wd_host: 'http://localhost:4444/wd/hub'
                        capabilities:
                            platform: 'WINDOWS'
                                version: ''
                                browser: 'chrome'
                                browserName: 'chrome'
```

**Note**: you may need to tweak the above configuration to suit your setup; the `base_url` and `wd_host` parameters may be different in your setup.

# Running Behat with Selenium
To run Behat with Selenium, you just need to ensure that your `behat.yml` file has been configured correctly to use Selenium, and that you have started the Selenium Grid and Node as explained previously in this article.

Run Behat as you normally would, for example:
```
./vendor/bin/behat
```
# Glossary
- **Selenium**: Selenium is a powerful browser automation tool. It can be driven by Selenese to interact and automate actions in a real browser
- - **Grid**: The Grid is a central hub which manages Selenium jobs among its available workers / nodes
- - **Node**: A node is responsible for launching the browser instance and controlling the actions inside of the browser
- **WebDriver**: A webdriver is a specific browser instance which Selenium is able to take control of. The browser is controlled by the WebDriver API, which Behat's Mink is responsible for
- **Guest Machine**: A guest machine is a virtual machine which runs inside of a regular operating system. Useful for controlling development dependencies (such as PHP version,  web server version, modules, etc).
- **Host Machine**: The operating system running on your computer, not in a VM.
- **Behat**: Behat is a BDD automation framework and runner.
- **Mink**: Mink is a standalone tool which acts as a [Facade](https://en.wikipedia.org/wiki/Facade_pattern) to many different browser automation drivers. Mink lets you write code which will be able to control Selenium2 or Goutte, with minimal changes.
- **MinkExtension**: This is a bridge between Behat and Mink; it allows Behat to interact with Mink.