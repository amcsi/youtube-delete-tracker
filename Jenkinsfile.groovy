pipeline {
    agent {
        dockerfile true
    }

    stages {
        stage('Run tests') {
            steps {
                sh 'vendor/bin/phpunit'
            }
        }
    }
}
