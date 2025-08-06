Imports System
Imports System.Windows.Forms
Imports System.IO.Ports
Imports System.Text.RegularExpressions
Imports System.Threading

Public Class SerialPortReceiver
    Private WithEvents serialPort As SerialPort
    Private textBox As TextBox
    Public WithEvents ssport As New SerialPort()
    Private scale As String = "scale1"
    Public serialstatus As Boolean = False
    Private myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
    Private uiThread As Thread ' UI thread reference

    Sub connect(ByVal xscale As String, targetTextBox As TextBox)
        Dim scale1 As Object = Nothing
        scale = xscale
        textBox = targetTextBox
        Try

            If ssport.IsOpen = False Then

                If scale = "scale2" Then

                    If myreg.TryGetValue("comport2", scale1) Then
                        ssport.PortName = myreg("comport2")
                        ssport.BaudRate = myreg("scale2_baudrate")
                        ssport.Parity = myreg("scale2_parity")
                        ssport.DataBits = myreg("scale2_databits")
                        ssport.StopBits = myreg("scale2_stopbits")
                        ssport.Open()


                    End If

                Else

                    If myreg.TryGetValue("comport", scale1) Then
                        'vbCr : - return to line beginning
                        'vbCrLf : - similar to pressing Enter
                        'vbLf : - go to next line
                        ssport.PortName = myreg("comport")
                        ssport.BaudRate = myreg("scale1_baudrate")
                        ssport.Parity = myreg("scale1_parity")
                        ssport.DataBits = myreg("scale1_databits")
                        ssport.StopBits = myreg("scale1_stopbits")
                        ssport.NewLine = ","
                        ssport.Open()
                    End If


                End If
                ssport.ReadTimeout = 1000
                If ssport.IsOpen Then
                    serialstatus = True
                Else
                    serialstatus = False
                End If


            End If

        Catch ex As Exception
            LogError(ex)
        End Try

    End Sub

    Sub Close()
        If ssport.IsOpen() = True Then
            ssport.Close()
        End If

    End Sub

    Private Sub SerialPort_DataReceived(sender As Object, e As SerialDataReceivedEventArgs) Handles ssport.DataReceived
        'Dim receivedData As String = ssport.ReadExisting()
        Try
            Dim receivedData2 As String = ssport.ReadLine()
            'Dim receivedData2 As String = ssport.ReadExisting()
            DebugLog(receivedData2)
            UpdateTextBox(receivedData2)
        Catch ex As Exception
            LogError(ex)
        End Try

    End Sub

    Private Sub UpdateTextBox(data As String)
        Dim myval As Double
        Dim mydbl As New Double
        Dim xdata As String
        Dim confac As New Integer
        Dim chkstr As String()
        Dim mystring As String
        Dim mydata As String

        Try
            If scale = "scale2" Then
                confac = CInt(myreg("scale2_conversion"))
            Else
                confac = CInt(myreg("scale1_conversion"))
            End If


            'If data.IndexOf("+x!") >= 0 Then
            '    chkstr = data.Split("+x!")
            '    mystring = chkstr(1)

            'ElseIf data.IndexOf("+p!") >= 0 Then
            '    chkstr = data.Split("+x!")
            '    mystring = chkstr(1)

            ' Else
            mystring = data
            'End If

            ' mydata = Regex.Replace(mystring, "[^\d-]+", "")
            mydata = Regex.Replace(mystring, "[^0-9]", "")
            If data <> "" Then
                If Double.TryParse(mydata, mydbl) Then
                    myval = CDbl(mydata) / confac
                    mydbl = Math.Round(myval, 6)

                    mydbl.ToString("0.000000")
                Else
                    mydbl = 0
                End If
            Else
                mydbl = 0

            End If

            If textBox.InvokeRequired Then
                'textBox.Invoke(Sub() textBox.AppendText(data))
                textBox.BeginInvoke(Sub()
                                        textBox.Text = mydbl.ToString("0.000000")
                                    End Sub)
            Else
                'textBox.AppendText(data)
                textBox.Text = mydbl.ToString("0.000000")
                DebugLog(mydbl)
            End If

        Catch ex As Exception

            MsgBox(ex)
            LogError(ex)
        End Try
    End Sub

End Class
