def dockerImage
def dockerImageTag

pipeline {
    agent any

    stages {
        stage('Build image') {
            steps {
                script {
                    dockerImage = docker.build("youtube-delete-tracker:${env.BRANCH_NAME}-${env.BUILD_ID}")
                    dockerImageTag = dockerImage.id
                }
            }
        }
        stage('Run tests') {
            steps {
                script {
                    dockerImage.inside('--network szeremi -e DB_HOST=mysql -e APP_ENV=testing') {
                        sh 'cd /var/www && php artisan migrate:fresh && vendor/bin/phpunit'
                    }
                }
            }
        }
        stage('Deploy') {
            when {
                anyOf {
                    branch 'master'
                    branch 'jenkins-declarative'
                }
            }
            steps {
                script {
                    def tag = 'youtube-delete-tracker'
                    if (env.BRANCH_NAME != 'master') {
                        // Implicit :latest must be changed to the branch name.
                        tag = "${tag}:${env.BRANCH_NAME}"
                    }
                    // Re-tag the image, because the deployment server is the same as the Jenkins server.
                    sh "docker tag ${dockerImage.id} ${tag}"
                }
            }
        }
    }
    post {
        always {
            // Clean up the tag.
            sh "docker rmi ${dockerImageTag}"
        }
    }
}
