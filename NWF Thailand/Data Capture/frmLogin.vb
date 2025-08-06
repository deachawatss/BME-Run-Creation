Imports Newtonsoft.Json.Linq

Public Class frmLogin
    Public mydict As New Dictionary(Of String, String)
    Dim api As New ApiClass()
    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Application.ExitThread()
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click

        Dim postData As New Dictionary(Of String, String) From {
                 {"uname", txtuname.Text},
                 {"pword", txtpwd.Text},
                 {"api_key", ""}
            }

        Dim myrequest = api.post_request("Auth/login", postData)
        Dim jsonObject As JObject = DirectCast(myrequest, JObject)
        Dim jsonObject2 As JObject = DirectCast(myrequest.Item("results"), JObject)
        Dim userdict As Dictionary(Of String, Object) = JObjectToDictionary(jsonObject2)

        Debug.WriteLine(jsonObject)

        If jsonObject.ContainsKey("isLogin") And jsonObject.Item("isLogin").ToString = "True" Then
            UserInfo.userinfo = userdict
            frmProdInfo.reloadfrm()
            frmProdInfo.Show()
            Me.Close()
        Else
            MsgBox("Invalid Username / Password", MsgBoxStyle.Information, "NWF Data Capture")
        End If


    End Sub
End Class