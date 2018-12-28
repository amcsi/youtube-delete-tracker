pipeline {
    agent {
        dockerfile true
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
