pipeline {
    agent {
        dockerfile true
    }

    stages {
        stage('Run tests') {
            steps {
                sh 'ls -al'
                sh 'pwd'
                sh 'ls -al /'
                sh 'ls -al vendor/bin'
                sh 'vendor/bin/phpunit'
            }
        }
    }
}
