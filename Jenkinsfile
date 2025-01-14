pipeline {
    agent any
    environment {
        // Map of servers and their respective paths
        servers = [
            "3.9.225.94" : "/var/lib/jenkins/workspace/cohezia",  
            "3.9.225.94" : "/home2/coheziatest"
        ]
    }
    stages {
        stage('Deploy to Remote') {
            steps {
                script {
                    // Find files modified in the last 10 minutes
                    def filesToDeploy = sh(script: "find ${env.WORKSPACE} -type f -mmin -10 | grep -v '.git' | grep -v 'Jenkinsfile'", returnStdout: true).trim().split("\n")
                    
                    // Check if files were found
                    if (filesToDeploy.size() > 0) {
                        // Iterate over each server and deploy
                        servers.each { server, serverPath ->
                            // Deploy each file to the remote server
                            filesToDeploy.each { fileName ->
                                // Clean the file path and remove the job name part
                                def fil = fileName.replaceAll("${JOB_NAME}", "").trim()

                                // Ensure the file path is properly escaped
                                def remoteFilePath = "${serverPath}${fil}"

                                // Deploy using SCP
                                try {
                                    // Ensure file paths are correct and that $WORKSPACE is correctly interpolated
                                    sh """
                                        scp -r ${WORKSPACE}${fil} root@${server}:${remoteFilePath}
                                    """
                                    echo "Successfully deployed: ${fil} to ${server} at ${remoteFilePath}"
                                } catch (Exception e) {
                                    echo "Error deploying file: ${fil} to ${server} at ${remoteFilePath}"
                                    currentBuild.result = 'FAILURE'
                                }
                            }
                        }
                    } else {
                        echo "No files modified in the last 10 minutes. Nothing to deploy."
                    }
                }
            }
        }
    }
}
