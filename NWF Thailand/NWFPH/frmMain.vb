
Public Class frmMain
    Public userdata As Dictionary(Of String, Object)
    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load


        Try


        Catch ex As Exception
            LogError(ex)

        End Try


        If isLogin = 0 Then
            Me.Hide()
            frmLogin.Show()
        End If

    End Sub

    Private Sub AboutToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles AboutToolStripMenuItem.Click

        frmAbout.ShowDialog()
    End Sub

    Private Sub AToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles AToolStripMenuItem.Click
        Dim mm As New frmConfig
        mm.ShowDialog()
    End Sub

    Private Sub LogoutToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles LogoutToolStripMenuItem.Click
        Me.Hide()
        frmLogin.Show()
        isLogin = 0
    End Sub

    Private Sub PartialPreweighToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles PartialPreweighToolStripMenuItem.Click
        'frmPartialPick.MdiParent = Me
        frmPartialPick.ShowDialog()
    End Sub
End Class
