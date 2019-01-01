pipeline {
    agent {
        dockerfile {
            args '-w /var/www'
        }
    }

    stages {
        stage('Run tests') {
            steps {
                sh 'pwd'
                sh 'vendor/bin/phpunit'
            }
        }
    }
}
