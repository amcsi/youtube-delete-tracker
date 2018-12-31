pipeline {
    agent {
        dockerfile true
    }

    stages {
        stage('Run tests') {
            steps {
                sh 'cd /var/www && pwd'
                sh 'pwd'
                sh 'cd /var/www && pwd'
            }
        }
    }
}
