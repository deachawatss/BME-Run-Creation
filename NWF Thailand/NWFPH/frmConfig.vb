Imports Microsoft.Win32
Imports Newtonsoft.Json.Linq
Imports System.IO.Ports
Imports System
Imports System.ComponentModel
Imports System.Drawing.Printing

Public Class frmConfig
    Dim dictionary As New Dictionary(Of String, Object)()
    Private Async Sub frmConfig_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim portNames As String() = SerialPort.GetPortNames()
        'Dim pbcode As New BarcodePrinter
        'Dim zpl As New ZPLConnection
        Dim mysec As New EncryptionHelper()

        Dim printers As PrinterSettings.StringCollection = PrinterSettings.InstalledPrinters

        'Dim mxypport As ArrayList = BarcodePrinter.UsbDriverlessTest()
        'Dim mypport As ArrayList = zpl.UsbDriverlessTest()

        Try
            'For Each item As String In mypport
            '    ' Your code here, e.g.:
            '    'Debug.WriteLine(item.ToString)
            '    txtPrinter.Items.Add(item.ToString)
            'Next

            For Each printerName As String In printers
                Dim printerInfo As New PrinterSettings()
                printerInfo.PrinterName = printerName
                txtPrinter.Items.Add(printerName.ToString)

                If printerName.ToUpper().Contains("USB") Then

                End If

            Next

        Catch ex As Exception
            LogError(ex)

        End Try


        For Each portName As String In portNames
            txtComPort.Items.Add(portName)
        Next

        'Dim myreg As New RegistryCRUD()
        dictionary = ReadXmlFileToDictionary()
        Dim myreg As Dictionary(Of String, Object) = ReadXmlFileToDictionary()


        Dim uname As Object
        Dim pword As Object
        Dim bme_server As Object
        Dim bme_uname As Object
        Dim bme_pword As Object
        Dim bme_database As Object
        Dim nwf_type As Object
        Dim nwf_port As Object
        Dim nwf_server As Object
        Dim nwf_uname As Object
        Dim nwf_pword As Object
        Dim nwf_database As Object

        Dim pc_id As Object
        Dim comport As Object
        Dim scale_interval As Object
        Dim nwf_printer As Object
        Dim comport2 As String
        Dim scale_interval2 As String

        Dim scale1_parity As Object
        Dim scale1_baudrate As Object
        Dim scale1_databits As Object
        Dim scale1_stopbits As Object
        Dim scale1_conversion As Object

        Dim scale2_parity As Object
        Dim scale2_baudrate As Object
        Dim scale2_databits As Object
        Dim scale2_stopbits As Object
        Dim scale2_conversion As Object

        Try
            uname = myreg("local_uname")
            txtuname.Text = uname
        Catch ex As Exception
            LogError(ex)

        End Try

        Try
            pword = mysec.DecryptString(myreg("local_pword"))
            txtpword.Text = pword
        Catch ex As Exception
            LogError(ex)

        End Try

        Try
            bme_server = myreg("bme_server")
            txtbmeserver.Text = bme_server
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            bme_uname = myreg("bme_uname")
            txtbmeuname.Text = bme_uname
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            bme_pword = mysec.DecryptString(myreg("bme_pword"))
            txtbmepword.Text = bme_pword
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            bme_database = myreg("bme_database")
            txtbmedatabase.Text = bme_database
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            nwf_type = myreg("nwf_type")
            combonwftype.Text = nwf_type
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            nwf_port = myreg("nwf_port")
            txtnwfport.Text = nwf_port
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            nwf_server = myreg("nwf_server")
            txtnwfserver.Text = nwf_server
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            nwf_uname = myreg("nwf_uname")
            txtnwfuname.Text = nwf_uname
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            nwf_pword = mysec.DecryptString(myreg("nwf_pword").ToString)
            Debug.WriteLine(mysec.DecryptString(myreg("nwf_pword").ToString))
            txtnwfpword.Text = nwf_pword
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            pc_id = myreg("pc_id")
            txtPCID.Text = pc_id
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            comport = myreg("comport")
            txtComPort.Text = comport
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            comport2 = myreg("comport2")
            txtComPort2.Text = comport2
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale_interval = myreg("scale1_interval")
            txtScaleInterval.Text = scale_interval
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale_interval2 = myreg("scale2_interval")
            txtScaleInterval2.Text = scale_interval2
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            nwf_printer = myreg("nwf_printer")
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            nwf_database = myreg("nwf_database")
            txtnwfdatabase.Text = nwf_database
        Catch ex As Exception
            LogError(ex)
        End Try


        Try
            scale1_parity = myreg("scale1_parity")
            If scale1_parity <> "" Then
                Select Case scale1_parity
                    Case 0
                        txtscale1parity.SelectedIndex = 0
                    Case 1
                        txtscale1parity.SelectedIndex = 1
                    Case 2
                        txtscale1parity.SelectedIndex = 2
                    Case 4
                        txtscale1parity.SelectedIndex = 3
                End Select
            End If
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale1_baudrate = myreg("scale1_baudrate")
            txtscale1baudrate.Text = scale1_baudrate
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale1_databits = myreg("scale1_databits")
            txtscale1databits.Text = scale1_databits
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale1_stopbits = myreg("scale1_stopbits")
            If scale1_stopbits <> "" Then
                Select Case scale1_stopbits
                    Case 0
                        txtscale1stopbits.SelectedIndex = 0
                    Case 1
                        txtscale1stopbits.SelectedIndex = 1
                End Select
            End If

        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale1_conversion = myreg("scale1_conversion")
            txtscale1conversion.Text = scale1_conversion
        Catch ex As Exception
            LogError(ex)
        End Try


        Try
            scale2_parity = myreg("scale2_parity")
            If scale2_parity <> "" Then
                Select Case scale2_parity
                    Case 0
                        txtscale2parity.SelectedIndex = 0
                    Case 1
                        txtscale2parity.SelectedIndex = 1
                    Case 2
                        txtscale2parity.SelectedIndex = 2
                    Case 4
                        txtscale2parity.SelectedIndex = 3
                End Select
            End If

        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale2_baudrate = myreg("scale2_baudrate")
            txtscale2baudrate.Text = scale2_baudrate
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale2_databits = myreg("scale2_databits")
            txtscale2databits.Text = scale2_databits
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale2_stopbits = myreg("scale2_stopbits")

            If scale2_stopbits <> "" Then
                Select Case scale2_stopbits
                    Case 0
                        txtscale2stopbits.SelectedIndex = 0
                    Case 1
                        txtscale2stopbits.SelectedIndex = 1
                End Select
            End If

        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            scale2_conversion = myreg("scale2_conversion")
            txtscale2conversion.Text = scale2_conversion
        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            Dim autofetch = 0

            If myreg.TryGetValue("autofetch", autofetch) Then
                If autofetch = 1 Then
                    AutoFetchOn.Checked = True
                End If
            End If

        Catch ex As Exception
            LogError(ex)
        End Try

        Try
            Dim debugstatus = 0

            If myreg.TryGetValue("debugstatus", debugstatus) Then
                If debugstatus = 1 Then
                    DebugOn.Checked = True
                End If
            End If

        Catch ex As Exception
            LogError(ex)
        End Try


        'Dim usbPrinters As List(Of String) = Await zpl.UsbTest()

        'For Each printer As String In usbPrinters
        'Debug.WriteLine(printer)
        'Next


    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs)
        Dim myreg As New RegistryCRUD()
        Dim mysec As New EncryptionHelper()
        dictionary.Add("local_uname", txtuname.Text)
        dictionary.Add("local_pword", mysec.EncryptString(txtpword.Text))

        ' Convert dictionary to XML file
        ConvertDictionaryToXmlFile(dictionary)

        MsgBox("Successfully Updated", MsgBoxStyle.Information)
    End Sub

    Private Sub btnbmesave_Click(sender As Object, e As EventArgs)
        Dim myreg As New RegistryCRUD()
        Dim mysec As New EncryptionHelper()

        dictionary.Add("bme_server", txtbmeserver.Text)
        dictionary.Add("bme_uname", txtbmeuname.Text)
        dictionary.Add("bme_pword", mysec.EncryptString(txtbmepword.Text))
        dictionary.Add("bme_database", txtbmedatabase.Text)

        ' Convert dictionary to XML file
        ConvertDictionaryToXmlFile(dictionary)
        MsgBox("Successfully Updated", MsgBoxStyle.Information)
    End Sub

    Private Sub btnnwfsave_Click(sender As Object, e As EventArgs)
        Dim myreg As New RegistryCRUD()
        Dim mysec As New EncryptionHelper()

        dictionary.Add("nwf_server", txtnwfserver.Text)
        dictionary.Add("nwf_type", combonwftype.Text)
        dictionary.Add("nwf_uname", txtnwfuname.Text)
        dictionary.Add("nwf_pword", mysec.EncryptString(txtnwfpword.Text))
        dictionary.Add("nwf_port", txtnwfport.Text)
        dictionary.Add("nwf_database", txtnwfdatabase.Text)

        ' Convert dictionary to XML file
        ConvertDictionaryToXmlFile(dictionary)

        MsgBox("Successfully Updated", MsgBoxStyle.Information)

    End Sub

    Private Sub btnPCProfile_Click(sender As Object, e As EventArgs)
        Dim myreg As New RegistryCRUD()



        dictionary.Add("pc_id", txtPCID.Text)
        dictionary.Add("comport", txtComPort.Text)
        dictionary.Add("scale_interval", txtScaleInterval.Text)
        dictionary.Add("comport2", txtComPort2.Text)
        dictionary.Add("scale_interval2", txtScaleInterval2.Text)
        dictionary.Add("nwf_printer", txtPrinter.Text)

        ' Convert dictionary to XML file
        ConvertDictionaryToXmlFile(dictionary)

        MsgBox("Successfully Updated", MsgBoxStyle.Information)
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs)
        Dim mystring As String
        Dim myreg As New RegistryCRUD()
        Dim nwf_printer As Object = myreg.ReadRegistryValue("nwf_printer", Nothing)

        mystring = String.Format("
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW812
                            ^LL0203
                            ^LS0
                            ^FT788,164^A0I,39,36^FH\^FDItem key:^FS
                            ^FT788,120^A0I,39,36^FH\^FDLot No:^FS
                            ^FT788,79^A0I,39,36^FH\^FDQuantity:^FS
                            ^FT636,164^A0I,39,36^FH\^FD{0}^FS
                            ^FT636,79^A0I,39,36^FH\^FD{2}^FS
                            ^FT636,119^A0I,39,36^FH\^FD{1}^FS
                            ^BY2,3,44^FT554,27^BCI,,Y,N
                            ^FD{3}^FS
                            ^FT344,163^A0I,39,36^FH\^FDBatch No:^FS
                            ^FT193,163^A0I,39,36^FH\^FD{4}^FS
                            ^PQ1,0,1,Y^XZ
                            ", "test", "test", "test", "test", "test")

        BarcodePrinter.SendZplOverUsb(mystring, nwf_printer)

    End Sub

    Private Sub Button3_Click(sender As Object, e As EventArgs) Handles Button3.Click
        Dim parity_dic As New Dictionary(Of String, Integer)
        Dim stopbit_dic As New Dictionary(Of String, Integer)

        parity_dic.Add("None", 0)
        parity_dic.Add("Odd", 1)
        parity_dic.Add("Even", 2)
        parity_dic.Add("Space", 4)

        stopbit_dic.Add("One", 1)
        stopbit_dic.Add("None", 0)

        Dim mysec As New EncryptionHelper()
        dictionary("bme_server") = txtbmeserver.Text
        dictionary("bme_uname") = txtbmeuname.Text
        dictionary("bme_pword") = mysec.EncryptString(txtbmepword.Text)
        dictionary("bme_database") = txtbmedatabase.Text

        dictionary("pc_id") = txtPCID.Text
        dictionary("nwf_printer") = txtPrinter.Text

        dictionary("comport") = txtComPort.Text
        dictionary("scale1_interval") = txtScaleInterval.Text
        dictionary("scale1_parity") = parity_dic(txtscale1parity.Text)
        dictionary("scale1_baudrate") = txtscale1baudrate.Text
        dictionary("scale1_databits") = txtscale1databits.Text
        dictionary("scale1_stopbits") = stopbit_dic(txtscale1stopbits.Text)
        dictionary("scale1_conversion") = txtscale1conversion.Text

        dictionary("comport2") = txtComPort2.Text
        dictionary("scale2_interval") = txtScaleInterval2.Text
        dictionary("scale2_parity") = parity_dic(txtscale2parity.Text)
        dictionary("scale2_baudrate") = txtscale2baudrate.Text
        dictionary("scale2_databits") = txtscale2databits.Text
        dictionary("scale2_stopbits") = stopbit_dic(txtscale2stopbits.Text)
        dictionary("scale2_conversion") = txtscale2conversion.Text

        dictionary("local_uname") = txtuname.Text
        dictionary("local_pword") = mysec.EncryptString(txtpword.Text)

        dictionary("nwf_server") = txtnwfserver.Text
        dictionary("nwf_type") = combonwftype.Text
        dictionary("nwf_uname") = txtnwfuname.Text
        dictionary("nwf_pword") = mysec.EncryptString(txtnwfpword.Text)
        dictionary("nwf_port") = txtnwfport.Text
        dictionary("nwf_database") = txtnwfdatabase.Text

        If AutoFetchOff.Checked = True Then
            dictionary("autofetch") = 0
        Else
            dictionary("autofetch") = 1
        End If

        If DebugOff.Checked = True Then
            dictionary("debugstatus") = 0
        Else
            dictionary("debugstatus") = 1
        End If

        Dim computerName As String = Environment.MachineName
        dictionary("computername") = computerName

        ConvertDictionaryToXmlFile(dictionary)

    End Sub

    Private Sub Button1_Click_1(sender As Object, e As EventArgs) Handles Button1.Click
        Dim parity_dic As New Dictionary(Of String, Integer)
        Dim stopbit_dic As New Dictionary(Of String, Integer)
        Dim frcom As New FrmComTest
        parity_dic.Add("None", 0)
        parity_dic.Add("Odd", 1)
        parity_dic.Add("Even", 2)
        parity_dic.Add("Space", 4)

        stopbit_dic.Add("One", 1)
        stopbit_dic.Add("None", 0)

        frcom.serialPort.PortName = txtComPort.Text
        frcom.serialPort.BaudRate = txtscale1baudrate.Text
        frcom.serialPort.Parity = parity_dic(txtscale1parity.Text)
        frcom.serialPort.DataBits = txtscale1databits.Text
        frcom.serialPort.StopBits = stopbit_dic(txtscale1stopbits.Text)
        frcom.ShowDialog()
    End Sub

    Private Sub Button2_Click_1(sender As Object, e As EventArgs) Handles Button2.Click
        Dim parity_dic As New Dictionary(Of String, Integer)
        Dim stopbit_dic As New Dictionary(Of String, Integer)
        Dim frcom As New FrmComTest
        parity_dic.Add("None", 0)
        parity_dic.Add("Odd", 1)
        parity_dic.Add("Even", 2)
        parity_dic.Add("Space", 4)

        stopbit_dic.Add("One", 1)
        stopbit_dic.Add("None", 0)

        frcom.serialPort.PortName = txtComPort2.Text
        frcom.serialPort.BaudRate = txtscale2baudrate.Text
        frcom.serialPort.Parity = parity_dic(txtscale2parity.Text)
        frcom.serialPort.DataBits = txtscale2databits.Text
        frcom.serialPort.StopBits = stopbit_dic(txtscale2stopbits.Text)
        frcom.ShowDialog()
    End Sub

End Class