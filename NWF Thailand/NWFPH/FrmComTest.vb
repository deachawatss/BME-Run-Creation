Imports System.ComponentModel
Imports System.IO.Ports

Public Class FrmComTest
    Public serialPort As New SerialPort()
    Private Sub FrmComTest_Closing(sender As Object, e As CancelEventArgs) Handles Me.Closing
        If serialPort.IsOpen Then
            serialPort.Close()
        End If

    End Sub

    Private Sub FrmComTest_Load(sender As Object, e As EventArgs) Handles Me.Load
        Try
            serialPort.Open()
        Catch ex As Exception

        End Try

        Timer1.Start()


    End Sub

    Private Sub Timer1_Tick(sender As Object, e As EventArgs) Handles Timer1.Tick
        Try
            Dim i = serialPort.ReadExisting
            If i <> "" Then
                TextBox1.Text = i
            End If

        Catch ex As Exception

        End Try
    End Sub
End Class