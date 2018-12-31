pipeline {
    agent {
        dockerfile true
    }

    stages {
        stage('Run tests') {
            steps {
                dir '/var/www' {
                    sh 'pwd'
                    sh 'pwd'
                    sh 'vendor/bin/phpunit'
                }
            }
        }
    }
}
