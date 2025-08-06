Public Class frmPassword
    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Dim mysec As New EncryptionHelper()
        TextBox2.Text = mysec.EncryptString(TextBox1.Text)
    End Sub
End Class