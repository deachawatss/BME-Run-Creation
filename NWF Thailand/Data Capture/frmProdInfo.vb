Public Class frmProdInfo
    Dim api As New ApiClass()
    Dim user = UserInfo.getUserinfo()
    Dim shift_member As List(Of String) = Nothing

    Private Sub frmProdInfo_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        If UserInfo.userinfo Is Nothing Then
            Me.Hide()
            frmLogin.ShowDialog()

        Else
            'lblTL.Text = UserInfo.Item("isLogin")
            'Dim user = UserInfo.getUserinfo()
            'Debug.WriteLine(user)
            lblTL.Text = user("Fname") & " " & user("Lname")
        End If
        txtDate.MaxDate = Today.AddDays(1)
        txtDate.MinDate = Today.AddDays(-1)
    End Sub

    Private Sub Button5_Click(sender As Object, e As EventArgs) Handles Button5.Click
        Me.Hide()
        UserInfo.userinfo = Nothing
        frmLogin.ShowDialog()
    End Sub

    Sub resetfield()
        'lblTL.Text = ""
        lblTM1.Text = ""
        lblTM2.Text = ""
        lblTM3.Text = ""
        lblTM4.Text = ""
        lblTM5.Text = ""
        lblTM6.Text = ""
        lblTM7.Text = ""
        lblTM8.Text = ""
        cmbSup.SelectedIndex = 0
        cmbShift.SelectedIndex = 0
        txtStart.Text = ""
        txtEnd.Text = ""
        txtDate.Value = Date.Today
    End Sub

    Sub reloadfrm()

        user = UserInfo.getUserinfo()
        lblTL.Text = user("Fname") & " " & user("Lname")
        resetfield()
    End Sub

    Private Sub cmbShift_SelectedIndexChanged(sender As Object, e As EventArgs) Handles cmbShift.SelectedIndexChanged

        If cmbShift.SelectedIndex <> 0 Then
            Dim postData As New Dictionary(Of String, String) From {
                 {"shift_time", cmbShift.Text},
                 {"shift_date", txtDate.Text},
                 {"api_key", "NWFTH"},
                 {"uname", user("uname")}
            }

            Dim myrequest = api.post_request("Datecapture/chktrans", postData)

        End If


    End Sub

    Private Sub btnStartShift_Click(sender As Object, e As EventArgs) Handles btnStartShift.Click

        'If cmbShift.Text = "" And
        '    txtDate.Text = "" And
        '    txtStart.Text = "" And
        '    txtEnd.Text = "" And
        '    cmbSup.Text = "" Then

        Dim postData As New Dictionary(Of String, String) From {
                {"shift_time", cmbShift.Text},
                {"shift_date", txtDate.Text},
                {"api_key", "NWFTH"},
                {"shift_start", txtStart.Text},
                {"shift_end", txtEnd.Text},
                {"shift_sv", cmbSup.Text},
                {"shift_tl", lblTL.Text}
           }
        Dim shfmm = ""

        If shift_member IsNot Nothing Then
            shfmm = String.Join(",", shift_member)
        End If

        postData.Add("shift_member", shfmm)


        Dim myrequest = api.post_request("DateCapture/starttrans", postData)
        btnEndShift.Enabled = True
        btnStartShift.Enabled = False
        'Debug.WriteLine(myrequest)

        ' End If



    End Sub

    Private Sub btnEndShift_Click(sender As Object, e As EventArgs) Handles btnEndShift.Click

    End Sub
End Class