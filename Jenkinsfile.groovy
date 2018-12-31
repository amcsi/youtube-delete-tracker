pipeline {
    agent {
        dockerfile true
    }

    stages {
        stage('Run tests') {
            steps {
                sh 'cd /var/www && vendor/bin/phpunit'
            }
        }
    }
}
