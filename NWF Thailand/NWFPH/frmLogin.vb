Imports System.ComponentModel
Imports System.Data.SqlClient
Imports System.IO
Imports Microsoft.Win32
Imports MySql.Data.MySqlClient

Public Class frmLogin

    ' TODO: Insert code to perform custom authentication using the provided username and password 
    ' (See https://go.microsoft.com/fwlink/?LinkId=35339).  
    ' The custom principal can then be attached to the current thread's principal as follows: 
    '     My.User.CurrentPrincipal = CustomPrincipal
    ' where CustomPrincipal is the IPrincipal implementation used to perform authentication. 
    ' Subsequently, My.User will return identity information encapsulated in the CustomPrincipal object
    ' such as the username, display name, etc.
    Dim mysec As New EncryptionHelper()
    Private Sub OK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles OK.Click
        Dim myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
        Dim mysec As New EncryptionHelper()

        Dim uname As Object
        Dim pword As Object

        Dim bmeserver As Object
        Dim nwfserver As Object
        Dim servetype As Object

        Try
            uname = myreg("local_uname")
            pword = mysec.DecryptString(myreg("local_pword"))
            bmeserver = myreg("bme_server")
            nwfserver = myreg("nwf_server")
            servetype = myreg("nwf_type")
        Catch ex As Exception
            bmeserver = Nothing
            nwfserver = Nothing
            servetype = Nothing
            LogError(ex)
        End Try

        'Try


        If bmeserver IsNot Nothing And nwfserver IsNot Nothing Then

            Dim dbuname As Object = myreg("nwf_uname")
            Dim dbpword As Object = myreg("nwf_pword")
            Dim dbport As Object = myreg("nwf_port")
            Dim dbname As Object = myreg("nwf_database")
            If servetype = "MYSQL" Then
                Dim db As New MySQL()
                Dim userData As Dictionary(Of String, Object) = db.ReadScalar("tbl_user", "*", String.Format("uname='{0}' and pword='{1}'", UsernameTextBox.Text, PasswordTextBox.Text))

                If userData.Count > 0 Then

                    'UserInfo.userinfo.Add("userid", userData.Item("userid").ToString)
                    'UserInfo.userinfo.Add("uname", userData.Item("uname").ToString)
                    'UserInfo.userinfo.Add("pword", userData.Item("pword").ToString)
                    'UserInfo.userinfo.Add("ulvl", userData.Item("ulvl").ToString)
                    'UserInfo.userinfo.Add("Fname", userData.Item("Fname").ToString)
                    'UserInfo.userinfo.Add("Lname", userData.Item("Lname").ToString)
                    'UserInfo.userinfo.Add("Position", userData.Item("Position").ToString)
                    UserInfo.userinfo = userData

                    frmMainBeta.userdata = UserInfo.getUserinfo()
                    Dim user = userData.Item("Fname").ToString & " " & userData.Item("Lname").ToString

                    If frmMainBeta.txtuser.Text <> user Then
                        frmMainBeta.txtuser.Text = user
                    End If

                    frmMainBeta.Show()
                    isLogin = 1
                    Me.Close()

                Else
                    MsgBox("Invalid Username / Password")

                End If
            Else
                Dim db As New MsSQL()
                Dim userData = db.ReadScalar("tbl_user", "*", String.Format("uname='{0}' and pword='{1}'", UsernameTextBox.Text, PasswordTextBox.Text))
            End If



        Else

            If uname = UsernameTextBox.Text And pword = PasswordTextBox.Text Then

                frmMainBeta.Show()
                UserInfo.isLogin = 1
                Me.Close()

            Else

                MsgBox("Please Login Setup Admin Account")

            End If


        End If

        ' Catch ex As Exception
        '    LogError(ex)
        'End Try


    End Sub

    Private Sub Cancel_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Cancel.Click


        Application.ExitThread()
    End Sub

    Private Sub frmLogin_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'Dim myreg As New RegistryCRUD()

        'Dim uname As Object = myreg.ReadRegistryValue("local_uname", Nothing)

        'If uname IsNot Nothing Then
        'btnInstall.Visible = False
        'End If

        ' Read XML file into dictionary
        Dim readDictionary As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
        If readDictionary IsNot Nothing Then
            btnUpload.Visible = False
            Button1.Visible = False
        End If

    End Sub

    Private Sub frmLogin_Closing(sender As Object, e As CancelEventArgs) Handles Me.Closing

        If isLogin = 0 Then

            Application.ExitThread()
        Else
            frmMainBeta.Show()
        End If
    End Sub

    Private Sub btnInstall_Click(sender As Object, e As EventArgs) Handles btnUpload.Click
        'Dim myreg As New RegistryCRUD()

        'myreg.CreateRegistryValue("local_uname", "admin", RegistryValueKind.String)
        'myreg.CreateRegistryValue("local_pword", "AriPos.89", RegistryValueKind.String)

        Dim dictionary As New Dictionary(Of String, Object)()
        dictionary.Add("local_uname", "admin")
        dictionary.Add("local_pword", mysec.EncryptString("password"))

        ' Convert dictionary to XML file
        ConvertDictionaryToXmlFile(dictionary)

        btnUpload.Visible = False
        MsgBox("Successfully Installed", MsgBoxStyle.Information, "NWF MOBILE")

    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Dim openFileDialog1 As New OpenFileDialog()
        openFileDialog1.Filter = "XML (*.xml)|*.txt|All Files (*.*)|*.*"
        'OpenFileDialog1.ShowDialog()
        If openFileDialog1.ShowDialog() = DialogResult.OK Then
            Dim selectedFilePath As String = openFileDialog1.FileName
            Dim appFolderPath As String = IO.Path.GetDirectoryName(Application.ExecutablePath)
            Dim fileName As String = Path.GetFileName(selectedFilePath)

            File.Copy(selectedFilePath, appFolderPath & "\" & fileName, True)

        End If
    End Sub
End Class
