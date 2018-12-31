pipeline {
    agent {
        dockerfile true
    }

    stages {
        stage('Run tests') {
            steps {
                sh 'cd /var/www && ls -al'
                sh 'vendor/bin/phpunit'
            }
        }
    }
}
