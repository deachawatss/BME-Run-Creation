Public Class frmTest
    Private ssport As New SerialHelper
    Private myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
    Private Sub frmTest_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ssport.connect("scale1")
        Timer1.Interval = myreg("scale1_interval")
        Timer1.Start()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        'ToolStripStatusLabel1
        ssport.Close()
        If ComboBox1.SelectedIndex = 0 Then
            'ssport.serialdata()
            ssport.connect("scale1")
            Timer1.Interval = myreg("scale1_interval")
        Else
            ssport.connect("scale2")
            Timer1.Interval = myreg("scale2_interval")
        End If
    End Sub

    Private Sub Timer1_Tick(sender As Object, e As EventArgs) Handles Timer1.Tick 
        TextBox1.Text = ssport.serialdata()
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click

    End Sub

    Private Sub TextBox1_TextChanged(sender As Object, e As EventArgs) 

    End Sub
End Class