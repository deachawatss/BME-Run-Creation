Public Class frmMainBeta
    Public userdata As Dictionary(Of String, Object)
    Private mymenu As String
    'Public ssport As New SerialHelper

    Private Sub frmMainBeta_Load(sender As Object, e As EventArgs) Handles MyBase.Load

        'If UserInfo.userinfo IsNot Nothing Then
        'Debug.WriteLine(UserInfo.userinfo("ulvl"))
        'If UserInfo.userinfo("ulvl") = 1 Then

        'btnconfig.Visible = True

        'End If
        'Else
        'btnconfig.Visible = False
        'End If
        Try


        Catch ex As Exception
            LogError(ex)

        End Try


        If isLogin = 0 Then
            Me.Hide()
            frmLogin.Show()
        End If
    End Sub

    Private Sub Panel2_Paint(sender As Object, e As PaintEventArgs)

    End Sub

    Private Sub PictureBox1_Click(sender As Object, e As EventArgs) Handles PictureBox1.Click

    End Sub

    Private Sub AddForm(frm As Form)

        PanelContainer.Controls.Clear()
        frm.TopLevel = False
        frm.TopMost = True
        frm.Dock = DockStyle.Fill
        PanelContainer.Controls.Add(frm)
        frm.Show()
    End Sub
    Private Sub resetmenucolor()
        btnHome.BackColor = Color.FromArgb(59, 186, 156)
        btnwt.BackColor = Color.FromArgb(59, 186, 156)
        btnscan.BackColor = Color.FromArgb(59, 186, 156)
        btnconfig.BackColor = Color.FromArgb(59, 186, 156)
        btnabout.BackColor = Color.FromArgb(59, 186, 156)
        btnInventory.BackColor = Color.FromArgb(59, 186, 156)
    End Sub
    Private Sub changeMenu(menu As String)
        resetmenucolor()

        If frmFetchWt.serialPort.IsOpen() Then
            frmFetchWt.serialPort.Close()
        End If

        Select Case menu
            Case "Home"
                lblTitle.Text = "HOME"
                btnHome.BackColor = Color.FromArgb(192, 255, 192)
            Case "PartialWt"
                lblTitle.Text = "PARTIAL WEIGHING"

                btnwt.BackColor = Color.FromArgb(192, 255, 192)

                If mymenu <> menu Then
                    'AddForm(frmPartialPick)
                    AddForm(frmPreweigh)
                End If

            Case "ScanToPrint"
                Dim frm As New frmScanToPrint
                btnscan.BackColor = Color.FromArgb(192, 255, 192)
                lblTitle.Text = "SCAN TO PRINT"

                If mymenu <> menu Then
                    AddForm(frm)
                End If

            Case "Configuration"
                Dim frm As New frmConfig
                lblTitle.Text = "SYSTEM CONFIGURATION"
                btnconfig.BackColor = Color.FromArgb(192, 255, 192)

                If mymenu <> menu Then
                    AddForm(frm)
                End If

            Case "About"
                lblTitle.Text = "SCAN TO PRINT"
                btnabout.BackColor = Color.FromArgb(192, 255, 192)

                If mymenu <> menu Then
                    AddForm(frmAbout)
                End If

            Case "Inventory"
                lblTitle.Text = "INVENTORY"
                btnInventory.BackColor = Color.FromArgb(192, 255, 192)
                Dim frm As New frminventory
                If mymenu <> menu Then
                    AddForm(frm)
                End If

        End Select

        mymenu = menu
    End Sub


    Private Sub btnscan_Click(sender As Object, e As EventArgs) Handles btnscan.Click
        changeMenu("ScanToPrint")
    End Sub

    Private Sub btnconfig_Click(sender As Object, e As EventArgs) Handles btnconfig.Click
        changeMenu("Configuration")
    End Sub

    Private Sub btnabout_Click(sender As Object, e As EventArgs) Handles btnabout.Click
        changeMenu("About")
    End Sub

    Private Sub btnwt_Click(sender As Object, e As EventArgs) Handles btnwt.Click
        changeMenu("PartialWt")
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Me.Hide()
        frmLogin.Show()
        isLogin = 0
    End Sub

    Private Sub btnInventory_Click(sender As Object, e As EventArgs) Handles btnInventory.Click
        changeMenu("Inventory")
    End Sub

    Private Sub frmMainBeta_FormClosing(sender As Object, e As FormClosingEventArgs) Handles Me.FormClosing
        '   ssport.Close()
    End Sub
End Class