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
                        sh 'cd /var/www/html && php artisan migrate:fresh && vendor/bin/phpunit'
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
                    // Stop the docker image running under the same tag.
                    // Then, systemd should automatically run the container again, but with the updated image.
                    sh "docker stop ${tag} || true"
                }
            }
        }
    }
    post {
        always {
            // Clean up the tag.
            sh "docker rmi ${dockerImageTag}"

            // Send email notification.
            emailext (
                body: '$DEFAULT_CONTENT',
                to: '$DEFAULT_RECIPIENTS',
                recipientProviders: [brokenTestsSuspects(), brokenBuildSuspects(), developers()],
                subject: '$DEFAULT_SUBJECT'
            )
        }
    }
}
