pipeline {
    agent {
        dockerfile {
            args '--network szeremi -e DB_HOST=mysql -e APP_ENV=testing'
        }
    }

    stages {
        stage('Run tests') {
            steps {
                sh 'cd /var/www && php artisan migrate:fresh && vendor/bin/phpunit'
            }
        }
    }
}
