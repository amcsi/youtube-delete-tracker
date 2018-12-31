pipeline {
    agent {
        dockerfile {
            dir '/var/www'
        }
    }

    stages {
        stage('Run tests') {
            steps {
                sh 'vendor/bin/phpunit'
            }
        }
    }
}
