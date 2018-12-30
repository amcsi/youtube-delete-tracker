pipeline {
    agent {
        docker { image 'mysql:5.6.37' }
    }

    stages {
        stage('Run tests') {
            steps {
                sh 'pwd'
                sh """
                  mysql -uroot -proot --version
                """

                echo "I'm executing in node: ${env.NODE_NAME}"
            }
        }
    }
}
