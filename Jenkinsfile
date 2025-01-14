pipeline {
    agent any
    environment {
        staging_server = "3.9.225.94"  
        remote_base_dir = "/home2/coheziatest"  // Ensure this is correctly set to your desired remote path
    }
    stages {
        stage('Deploy to Remote') {
            steps {
                script {
                    // Find files modified in the last 10 minutes
                    def filesToDeploy = sh(script: "find ${env.WORKSPACE} -type f -mmin -10 | grep -v '.git' | grep -v 'Jenkinsfile'", returnStdout: true).trim().split("\n")
                    
                    // Check if files were found
                    if (filesToDeploy.size() > 0) {
                        filesToDeploy.each { fileName ->
                            // Clean the file path and remove the job name part
                            def fil = fileName.replaceAll("${JOB_NAME}", "").trim()
                            
                            // Debugging: print out the paths being constructed
                            echo "File to deploy: ${fileName}"
                            echo "Cleaned file path: ${fil}"
                            
                            // Construct the remote file path dynamically
                            def remoteFilePath = "${remote_base_dir}${fil}"
                            echo "Remote file path: ${remoteFilePath}"

                            // Deploy using SCP
                            try {
                                // Ensure the local and remote paths are correct
                                sh """
                                    scp -r ${WORKSPACE}${fil} root@${staging_server}:${remoteFilePath}
                                """
                                echo "Successfully deployed: ${fil}"
                            } catch (Exception e) {
                                echo "Error deploying file: ${fil}"
                                currentBuild.result = 'FAILURE'
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
