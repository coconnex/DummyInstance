pipeline {
    agent any
    environment {
        staging_server = "3.9.225.94"  
    }
    stages {
        stage('Deploy to Remote') {
            steps {
                script {
                    // Find files modified in the last 10 minutes
                    def filesToDeploy = sh(script: 'find ${WORKSPACE} -type f -mmin -10 | grep -v ".git" | grep -v "Jenkinsfile"', returnStdout: true).trim().split("\n")

                    // Deploy each file to the remote server
                    filesToDeploy.each { fileName ->
                        // Clean the file path and remove the job name part
                        def fil = fileName.replaceAll("${JOB_NAME}", "").trim()

                        // Deploy using SCP (make sure to escape the variable)
                        sh "scp -r ${WORKSPACE}${fil} root@${staging_server}:/var/lib/jenkins/workspace/cohezia${fil}"
                    }
                }
            }
        }
    }
}
