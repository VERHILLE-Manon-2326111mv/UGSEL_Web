#  UGSEL Project - Web Platform

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-6.4%20LTS-000000?style=for-the-badge&logo=symfony&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-v4.59-2496ED?style=for-the-badge&logo=docker&logoColor=white)

Welcome to the **UGSEL** project repository.

The goal of this architecture is to provide a **robust, maintainable, and sustainable** solution, strictly adhering to **KISS** (*Keep It Simple, Stupid*) and **SOLID** principles.

---

##  1. Tech Stack

We have chosen a modern yet proven stack to ensure long-term stability.

| Technology | Version | Role | Why this choice? |
| :--- | :---: | :--- | :--- |
| **Symfony** | `6.4 LTS` | Backend Framework | "Long Term Support" version (until 2027). Maximum compatibility with the current ecosystem. |
| **PHP** | `8.3` | Language | Strong typing, JIT performance, native attributes. |
| **MySQL** | `8.0` | DBMS | Stable and high-performance industry standard. |
| **AssetMapper** | *Native* | Asset Management | **KISS Strategic Choice**: Removes Node.js/NPM complexity. Simplifies installation. |
| **Hotwire** | *Turbo* | Frontend | Smooth "Single Page App" experience without the complexity of REST API + React/Vue. |
| **Docker** | `v4.59` | Environment | Containerization of the DB and mail server for fast onboarding. |

---

##  2. Getting Started

Follow these steps to set up the project locally.

### Prerequisites
1. Clone the project.
2. Create the `.env` file from the `.env.example` file.

> ⚠️
> If you run `composer install` **before** creating the `.env` file, you will encounter an error. Make sure to create the environment file first.

### Installation

```bash
# Install PHP dependencies
composer install
```

### Base de donnée avec Docker
```bash
# Lancement des conteneurs (MySQL + Mailpit)
docker compose up -d
```

To access the database directly in the container:
```bash
docker exec -it ugsel_web-database-1 mysql -u root -p

# use app_db;
# SHOW DATABASES;
# SHOW TABLES;
```

To update the database with the latest migrations:
```bash
php bin/console doctrine:migrations:migrate^C        
php bin/console make:migration
```

For the creation of a database
```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test
```

To launch the test data to develop
```bash
php bin/console doctrine:fixtures:load
```

Symfony Server Launch
```bash
symfony server:start
```


##  3. Pull Request Policy

To maintain clean code and consistent architecture, the following rules apply:

* **Dedicated branches :** Each contributor must use a unique branch for each feature. This allows to check potential conflicts, protect the quality of the code and ensure a clean architecture.
* **Code Review :** Each Pull Request must be validated by a **"reviewer"**. This step allows for a critical and constructive external perspective before the merger.
---

##  4. Management of the Linters

In order to ensure the robustness and maintainability of the project, we have implemented a suite of automatic analysis and formatting tools.
### Formatting and Style (Visual Uniformity)
* **PHP-CS-Fixer** : Automatically reformat the PHP code according to **PSR-12** standards and Symfony recommendations.
    * *Installation :* `composer require --dev friendsofphp/php-cs-fixer`
    * *use :* `vendor/bin/php-cs-fixer fix`
* **Prettier** : Reformats JS (Stimulus) code, CSS and configuration files.
    * *Installation :* `npm install --save-dev prettier`
    * *use :* `npx prettier --write assets`

###  Static Analysis (Reliability and Logic)
* **PHPStan**: Analyzes PHP code without executing it to detect typing and logic errors.
    * *Installation :* `composer require --dev phpstan/phpstan`
    * *Use :* `vendor/bin/phpstan analyse src`
* **ESLint** : Identifies bad practices and unused variables in JavaScript files.
    * *Installation :* `npm install --save-dev eslint`
    * *Use :* `npx eslint assets`

### Automatic Modernization
* **Rector** : Tool dedicated to the migration of "Legacy" code and automatic upgrade to recent PHP versions.
    * *Installation :* `composer require --dev rector/rector`
    * *Use :* `vendor/bin/rector process`

>
>  These tools are integrated into **GitHub Actions**, but it is strongly advised to launch them locally before each commit.

###  Quick Commands (Makefile & PowerShell)
A `Makefile` is available to automate these tasks.

| Action              | Commande (Linux/Mac) | Commande (Windows PS) |
|:--------------------| :--- | :--- |
| **Format the code** | `make lint` | `.\make.ps1 lint` |
| **Static analysis** | `make fix` | `.\make.ps1 fix` |
| **Run all**         | `./Makefile` | *N/A* |

---

##  5. Test management

We use several layers of tests:

### Unit and Integration Tests (**PHPUnit**)
Used to control each feature during the development phase.
* **CI/CD Rule:** The pipeline imposes a minimum code coverage of **70%**.

### Behavior Tests (**Behat**)
These tests are centralized in the `test`branch. They allow for the validation of business scenarios.

### End-to-End Testing (**Panther**)
Also located in the `test`branch, they simulate the behavior of a real user in a browser.

## 6. Branch Protection & Workflow

To ensure the stability of the project, the **`main`** and **`dev`** branches are strictly protected via GitHub settings:

* **No Direct Push:** You cannot push code directly to these branches. Any attempt to do so will be rejected by the server.
* **Mandatory Pull Request:** All changes must go through a **Pull Request (PR)** from a feature branch.
* **Status Checks:** Before a PR can be merged into `dev` or `main`, all automated quality checks (CI/CD) must pass (Green).
* **Review Required:** At least one manual approval from a team member is required to authorize the merge.

**Workflow Summary:**
1. Create a feature branch from `dev`.
2. Commit your changes and push to your remote branch.
3. Open a Pull Request targeting `dev`.
4. Wait for the CI to pass and a reviewer to approve.
5. Merge your PR once all lights are green.

