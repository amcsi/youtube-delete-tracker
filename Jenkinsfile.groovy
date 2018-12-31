pipeline {
    agent {
        dockerfile {
            customWorkspace '/var/www'
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
