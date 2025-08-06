Imports System.IO.Ports
Imports System.Text.RegularExpressions

Public Class SerialHelper

    Public sport1 As New SerialPort()
    Public sport2 As New SerialPort()
    Public ssport As New SerialPort()
    Private scale As String = "scale1"
    Public serialstatus As Boolean = False

    Private myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()

    Sub connect1(ByVal scale As String)
        Dim scale1 As Object = Nothing
        Dim scale2 As Object = Nothing

        If sport1.IsOpen = False Then

            If myreg.TryGetValue("comport", scale1) Then
                If myreg("comport") IsNot "" Then
                    sport1.PortName = myreg("comport")
                    sport1.BaudRate = myreg("scale1_baudrate")
                    sport1.Parity = myreg("scale1_parity")
                    sport1.DataBits = myreg("scale1_databits")
                    sport1.StopBits = myreg("scale1_stopbits")
                    sport1.Open()
                End If
            End If

        End If

        If sport2.IsOpen = False Then

            If myreg.TryGetValue("comport2", scale2) Then
                If myreg("comport2") IsNot "" Then
                    sport2.PortName = myreg("comport2")
                    sport2.BaudRate = myreg("scale2_baudrate")
                    sport2.Parity = myreg("scale2_parity")
                    sport2.DataBits = myreg("scale2_databits")
                    sport2.StopBits = myreg("scale2_stopbits")
                    sport2.Open()
                End If
            End If

        End If


    End Sub

    Sub connect(ByVal xscale As String)
        Dim scale1 As Object = Nothing
        scale = xscale
        Try


            If ssport.IsOpen = False Then

                If scale = "scale2" Then

                    If myreg.TryGetValue("comport2", scale1) Then
                        If myreg("comport2") IsNot "" Then
                            ssport.PortName = myreg("comport2")
                            ssport.BaudRate = myreg("scale2_baudrate")
                            ssport.Parity = myreg("scale2_parity")
                            ssport.DataBits = myreg("scale2_databits")
                            ssport.StopBits = myreg("scale2_stopbits")
                            ssport.Open()

                            If ssport.IsOpen Then
                                serialstatus = True
                            Else
                                serialstatus = False
                            End If
                        End If

                    End If

                Else

                    If myreg.TryGetValue("comport", scale1) Then

                        If myreg("comport") IsNot "" Then

                            ssport.PortName = myreg("comport")
                            ssport.BaudRate = myreg("scale1_baudrate")
                            ssport.Parity = myreg("scale1_parity")
                            ssport.DataBits = myreg("scale1_databits")
                            ssport.StopBits = myreg("scale1_stopbits")
                            ssport.Open()

                            If ssport.IsOpen Then
                                serialstatus = True
                            Else
                                serialstatus = False
                            End If
                        End If
                    End If


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

    Function serialdata() As Double

        Dim myval As Double
        Dim mydbl As New Double
        Dim data As String
        Dim confac As New Integer
        Dim chkstr As String()
        Dim mystring As String
        Dim mydata As String

        If scale = "scale2" Then
            confac = CInt(myreg("scale2_conversion"))
        Else
            confac = CInt(myreg("scale1_conversion"))
        End If

        If ssport.IsOpen Then

            Try
                data = ssport.ReadExisting()
                data = data.Trim()
            Catch ex As Exception
                data = ""
                LogError(ex)
            End Try
        Else
            data = ""

        End If



        If data.IndexOf("+x!") >= 0 Then
            chkstr = data.Split("+x!")
            mystring = chkstr(1)

        ElseIf data.IndexOf("+p!") >= 0 Then
            chkstr = data.Split("+x!")
            mystring = chkstr(1)

        Else
            mystring = data
        End If

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

        Return mydbl

    End Function



End Class
