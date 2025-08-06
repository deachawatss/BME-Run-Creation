
Imports Newtonsoft.Json.Linq
Public Class frmPreweigh
    Dim api As New ApiClass()
    Public batchdic As Dictionary(Of String, Integer) = Nothing
    Public gbatchlist As String = String.Empty
    Public myformulaid As String = String.Empty
    Public searchby As String = String.Empty
    Public dgindex As Integer = 0
    Public frmdatas As Dictionary(Of String, String) = Nothing
    Public sellotno As String = String.Empty
    Public selitemkey As String = String.Empty
    Public selbinno As String = String.Empty
    Private Sub btnSearchRunNo_Click(sender As Object, e As EventArgs) Handles btnSearchRunNo.Click
        searchby = "runno"
        frmPreweighRunList.ShowDialog()
    End Sub

    Private Sub btnSearchBatchNo_Click(sender As Object, e As EventArgs) Handles btnSearchBatchNo.Click
        searchby = "batchno"
        frmPreweighBatch.myrunno = txtRunNo.Text
        frmPreweighBatch.ShowDialog()
    End Sub
    Public Sub setmydata(ByVal mydata As Dictionary(Of String, String))

        If mydata IsNot Nothing Then

            frmdatas = mydata
            txtRunNo.Text = mydata("RunNo")
            dgvpicked.Rows.Clear()
            dgpickedlist.Rows.Clear()
            dgvtopick.Rows.Clear()

            Dim postData As New Dictionary(Of String, String) From {
              {"runno", mydata("RunNo")},
              {"allergen", cmbAllergen.Text}
            }
            Dim myrequest = api.post_request("PartialPick/getruninfo", postData)
            'Debug.Write(myrequest)
            Dim jsonObject As JObject = DirectCast(myrequest, JObject)

            If jsonObject.ContainsKey("ToBePicked") Then
                Dim dta As JArray = jsonObject("ToBePicked")

                For Each item As JToken In dta
                    dgvtopick.Rows.Add({
                          item("ItemKey"),
                          item("BatchNo"),
                          item("req_qty"),
                          item("pick_qty"),
                          item("rqty"),
                          item("plotno"),
                          item("slotno"),
                          "",
                          item("featurevalue"),
                          item("wtfrom"),
                          item("wtto"),
                          item("Desc1"),
                          item("allergen"),
                          item("nbulk")
                      })

                Next

            End If

            If jsonObject.ContainsKey("PickedSummaryList") Then
                Dim dtaPickedList As JArray = jsonObject("PickedSummaryList")

                For Each itemPickedList As JToken In dtaPickedList
                    'Debug.WriteLine(item)
                    ' dgvpicked.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, pickedpartial, pbalance, item("lotno"), "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"))
                    dgvpicked.Rows.Add({
                          itemPickedList("ItemKey"),
                          itemPickedList("BatchNo"),
                          itemPickedList("req_qty"),
                          itemPickedList("pick_qty"),
                          itemPickedList("rqty"),
                          itemPickedList("plotno"),
                          itemPickedList("slotno"),
                          "",
                          itemPickedList("featurevalue"),
                          itemPickedList("wtfrom"),
                          itemPickedList("wtto"),
                          itemPickedList("Desc1"),
                          itemPickedList("allergen")
                      })

                Next

            End If

            If jsonObject.ContainsKey("PickedList") Then
                Dim dta As JArray = jsonObject("PickedList")
                Debug.WriteLine(dta)
                For Each item As JToken In dta
                    '  dgpickedlist.Rows.Add(item("itemkey"), item("batchno"), item("lotno"), item("qty"), item("tbl_rm_allocate_partial_id"))
                    dgpickedlist.Rows.Add({
                          item("itemkey"),
                          item("batchno"),
                          item("lotno"),
                          item("qty"),
                          item("tbl_rm_allocate_partial_id"),
                          item("allergen").ToString,
                          item("picked_expiry")
                      })

                Next

            End If

            If jsonObject.ContainsKey("topickdetail") Then
                Dim dta = jsonObject("topickdetail")
                txtFGitemkey.Text = dta("FormulaId")
                txtFGDesc.Text = dta("Desc1")
                txtproddate.Text = dta("SchStartDate")
                txtBatches.Text = mydata("Batch")
                txtfgsize.Text = mydata("BatchSize")
                'Debug.WriteLine(jsonObject("topickdetail"))
            End If


            If dgindex >= 0 AndAlso dgindex < dgvtopick.Rows.Count Then
                dgvtopick.Rows(dgindex).Selected = True
                'dgvtopick

                Dim e As New DataGridViewCellEventArgs(0, dgindex)
                dgvtopick_CellClick(dgvtopick, e)
            End If

        End If

    End Sub

    Public Sub setmydatabatch(ByVal mydata As Dictionary(Of String, String))
        txtRunNo.Text = mydata("RunNo")
        txtBatchno.Text = mydata("batchno")
        dgvpicked.Rows.Clear()
        dgpickedlist.Rows.Clear()
        dgvtopick.Rows.Clear()

        Dim postData As New Dictionary(Of String, String) From {
          {"runno", mydata("RunNo")},
          {"batchno", mydata("batchno")}
        }
        Dim myrequest = api.post_request("PartialPick/getruninfo", postData)
        'Debug.Write(myrequest)
        Dim jsonObject As JObject = DirectCast(myrequest, JObject)

        If jsonObject.ContainsKey("ToBePicked") Then
            Dim dta As JArray = jsonObject("ToBePicked")

            For Each item As JToken In dta
                dgvtopick.Rows.Add({
                      item("ItemKey"),
                      item("BatchNo"),
                      item("req_qty"),
                      item("pick_qty"),
                      item("rqty"),
                      item("plotno"),
                      item("slotno"),
                      "",
                      item("featurevalue"),
                      item("wtfrom"),
                      item("wtto"),
                      item("Desc1"),
                      item("allergen")
                  })

            Next

        End If

        If jsonObject.ContainsKey("PickedSummaryList") Then
            Dim dtaPickedList As JArray = jsonObject("PickedSummaryList")

            For Each itemPickedList As JToken In dtaPickedList
                'Debug.WriteLine(item)
                ' dgvpicked.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, pickedpartial, pbalance, item("lotno"), "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"))
                dgvpicked.Rows.Add({
                      itemPickedList("ItemKey"),
                      itemPickedList("BatchNo"),
                      itemPickedList("req_qty"),
                      itemPickedList("pick_qty"),
                      itemPickedList("rqty"),
                      itemPickedList("plotno"),
                      itemPickedList("slotno"),
                      "",
                      itemPickedList("featurevalue"),
                      itemPickedList("wtfrom"),
                      itemPickedList("wtto"),
                      itemPickedList("Desc1"),
                      itemPickedList("allergen")
                  })

            Next

        End If

        If jsonObject.ContainsKey("PickedList") Then
            Dim dta As JArray = jsonObject("PickedList")
            Debug.WriteLine(dta)
            For Each item As JToken In dta
                '  dgpickedlist.Rows.Add(item("itemkey"), item("batchno"), item("lotno"), item("qty"), item("tbl_rm_allocate_partial_id"))
                dgpickedlist.Rows.Add({
                      item("itemkey"),
                      item("batchno"),
                      item("lotno"),
                      item("qty"),
                      item("tbl_rm_allocate_partial_id"),
                      item("allergen").ToString,
                      item("picked_expiry")
                  })

            Next

        End If

        If jsonObject.ContainsKey("topickdetail") Then
            Dim dta = jsonObject("topickdetail")
            txtFGitemkey.Text = dta("FormulaId")
            txtFGDesc.Text = dta("Desc1")
            txtproddate.Text = dta("SchStartDate")
            txtBatches.Text = mydata("Batch")
            txtfgsize.Text = mydata("BatchSize")
            'Debug.WriteLine(jsonObject("topickdetail"))
        End If

    End Sub

    Private Sub dgvtopick_CellClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgvtopick.CellClick
        dgindex = e.RowIndex
        If e.RowIndex >= 0 Then
            Dim row As DataGridViewRow = dgvtopick.Rows(e.RowIndex)
            Dim item = row.Cells("item").Value
            Dim batchno = row.Cells("batchno").Value
            Dim partialdata = row.Cells("partialdata").Value
            Dim wt = row.Cells("wt").Value
            Dim balanced = row.Cells("balanced").Value
            Dim LotNo = row.Cells("LotNo").Value
            Dim BinNo = row.Cells("BinNo").Value
            Dim BagWt = If(row.Cells("BagWt").Value = Nothing, 0, row.Cells("BagWt").Value)
            Dim wtrangefrom = row.Cells("wtrangefrom").Value 'Math.Round((mPartial - (mPartial * wtfrom1p)), 6, MidpointRounding.AwayFromZero)
            Dim wtrangeto = row.Cells("wtrangeto").Value
            Dim desc = row.Cells("desc").Value
            Dim mysuggestedData = row.Cells("tp_slotno").Value

            Dim mysuggested = ""
            txtBatchno.Text = batchno
            txtItemKey.Text = item
            txtDesc.Text = desc
            txtlotno.Text = LotNo
            txtbin.Text = BinNo
            txtBagWt.Text = BagWt
            txtWt.Text = wt
            txtWtrangefrom.Text = wtrangefrom
            txtWtrangeto.Text = wtrangeto
            txtTotalNeeded.Text = partialdata
            txtRemainingQty.Text = balanced
            txtsuggestedlot.Text = mysuggestedData


        End If
    End Sub

    Private Sub dgvpicked_CellClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgvpicked.CellClick

        If e.RowIndex >= 0 Then
            Dim row As DataGridViewRow = dgvpicked.Rows(e.RowIndex)
            Dim item = row.Cells("pitem").Value
            Dim batchno = row.Cells("pbatchno").Value
            Dim partialdata = row.Cells("ppartialdata").Value
            Dim wt = row.Cells("pwt").Value
            Dim balanced = row.Cells("pbalanced").Value
            Dim LotNo = row.Cells("pLotNo").Value
            Dim BinNo = row.Cells("pBinNo").Value
            Dim BagWt = row.Cells("pBagWt").Value
            Dim wtrangefrom = row.Cells("pwtrangefrom").Value
            Dim wtrangeto = row.Cells("pwtrangeto").Value
            Dim desc = row.Cells("pdesc").Value
            txtBatchno.Text = batchno
            txtItemKey.Text = item
            txtDesc.Text = desc
            txtlotno.Text = LotNo
            txtbin.Text = BinNo
            txtBagWt.Text = BagWt
            txtWt.Text = wt
            txtWtrangefrom.Text = wtrangefrom
            txtWtrangeto.Text = wtrangeto
            txtTotalNeeded.Text = partialdata
            txtRemainingQty.Text = balanced
            txtsuggestedlot.Text = ""

        End If
    End Sub

    Private Sub dgpickedlist_CellContentDoubleClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgpickedlist.CellContentDoubleClick
        If e.RowIndex >= 0 Then
            Dim row As DataGridViewRow = dgpickedlist.Rows(e.RowIndex)
            Dim barcode As String = "(02)" & row.Cells("DataGridViewTextBoxColumn6").Value & "(10)" & row.Cells("DataGridViewTextBoxColumn8").Value
            Dim itemkey As String = row.Cells("DataGridViewTextBoxColumn6").Value
            Dim lotno As String = row.Cells("DataGridViewTextBoxColumn8").Value
            Dim qty As String = row.Cells("DataGridViewTextBoxColumn9").Value
            Dim batch As String = row.Cells("DataGridViewTextBoxColumn7").Value
            Dim mycode As String = row.Cells("partial_id").Value
            Dim printdate As String = Now.ToString("MM/dd/yyyy h:mm tt")

            Dim zbarcode = "nwf-pb-" & mycode.PadLeft(11, "0")
            Dim mystring As String
            'Dim myreg As New RegistryCRUD()
            Dim msdb As New MsSQL()

            Try
                Dim readDictionary As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
                Dim nwf_printer As Object = readDictionary("nwf_printer")

                Dim allergen = row.Cells("pick_allergen").Value
                Dim pickedexpiry = row.Cells("picked_expiry").Value
                'Dim alq = String.Format("Select * from v_item_allergen where itemkey = '{0}'", itemkey)

                If IsDBNull(allergen) Then
                    allergen = ""
                End If
                'Dim ald = msdb.SelectDataScalar(alq)
                ''allergen = ald("allergenx")
                ''Debug.WriteLine(ald("alelrgenx"))
                'If ald.Count > 0 And ald.TryGetValue("allergen", allergen) Then
                '    If IsDBNull(allergen) Then
                '        allergen = ""
                '    Else
                '        allergen = ald("allergen").ToString
                '    End If

                'Else
                '    allergen = ""
                'End If

                If nwf_printer IsNot Nothing Then

                    Dim filePath As String = "report\preweigh_wt.txt"
                    Dim fileContent As String = System.IO.File.ReadAllText(filePath)

                    'mystring = String.Format("
                    '                ^XA
                    '                ^MMT
                    '                ^PW812
                    '                ^LL0812
                    '                ^LS0
                    '                ^FO224,0^BQN,2,20^FDQA,{4}*{5}^FS
                    '                ^FT494,692^A0I,113,112^FH\^FD{0}^FS
                    '                ^FT716,595^A0I,99,98^FH\^FD{1}^FS
                    '                ^FT296,595^A0I,99,98^FH\^FDKG^FS
                    '                ^FT482,458^A0I,99,98^FH\^FD{2}^FS
                    '                ^FT795,370^A0I,56,55^FH\^FD{3}^FS
                    '                ^FT468,370^A0I,56,55^FH\^FD09/26/2024^FS
                    '                ^FT201,370^A0I,56,55^FH\^FD6:12 PM^FS
                    '                ^PQ1,0,1,Y^XZ


                    '            ", itemkey, qty, batch, lotno, itemkey, qty)

                    Dim templateFormat = fileContent.Replace("{itemkey}", itemkey)
                    templateFormat = templateFormat.Replace("{qty}", numberFormat(qty))
                    templateFormat = templateFormat.Replace("{batchno}", batch)
                    templateFormat = templateFormat.Replace("{lotno}", lotno)
                    templateFormat = templateFormat.Replace("{printdate}", printdate)
                    templateFormat = templateFormat.Replace("{zbarcode}", zbarcode)
                    templateFormat = templateFormat.Replace("{allergen}", allergen)
                    templateFormat = templateFormat.Replace("{expiry}", pickedexpiry)
                    mystring = templateFormat

                    RawPrint(nwf_printer, mystring)

                    'Dim frm As New RptPreweighfrm
                    'frm.itemkey = itemkey
                    'frm.lotno = lotno
                    'frm.qty = qty
                    'frm.batchno = batch

                    'frm.ShowDialog()

                    'mystring = String.Format("
                    '        ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
                    '        ^XA
                    '        ^MMT
                    '        ^PW812
                    '        ^LL0203
                    '        ^LS0
                    '        ^FT788,164^A0I,39,36^FH\^FDItem key:^FS
                    '        ^FT788,120^A0I,39,36^FH\^FDLot No:^FS
                    '        ^FT788,79^A0I,39,36^FH\^FDQuantity:^FS
                    '        ^FT636,164^A0I,39,36^FH\^FD{0}^FS
                    '        ^FT636,79^A0I,39,36^FH\^FD{2}^FS
                    '        ^FT636,119^A0I,39,36^FH\^FD{1}^FS
                    '        ^BY2,3,44^FT554,27^BCI,,Y,N
                    '        ^FD{3}^FS
                    '        ^FT344,163^A0I,39,36^FH\^FDBatch No:^FS
                    '        ^FT193,163^A0I,39,36^FH\^FD{4}^FS
                    '        ^FT342,79^A0I,39,36^FH\^FDAllergen:^FS
                    '        ^FT206,79^A0I,39,36^FH\^FD{5}^FS
                    '        ^PQ1,0,1,Y^XZ

                    '        ", itemkey, lotno, qty, zbarcode, batch, allergen)

                    'BarcodePrinter.SendZplOverUsb(mystring, nwf_printer)

                End If


            Catch ex As Exception
                LogError(ex)
                Debug.WriteLine(ex)
            End Try



        End If

    End Sub

    Private Sub txtItemKey_KeyPress(sender As Object, e As KeyPressEventArgs) Handles txtItemKey.KeyPress
        'Dim scanBuffer As
        'If e.KeyChar = vbCr Then
        ' Add the digit to the scan buffer
        'ProcessScan(txtItemKey.Text)
        'End If
    End Sub

    Public Sub ProcessScan(itemkey As String, lotno As String, binno As String)
        ' Do something with the scanned data, for example:
        'Dim mycode = mybarcode(Data)
        Dim myrec As Dictionary(Of String, String) = Nothing
        Dim nwfdb As New MySQL()
        Dim mfrm As New frmPreweighFetchWt
        Dim mrec As Boolean = False




        If (itemkey <> "" And lotno <> "") Then

            'Try
            txtDesc.Text = ""
            txtlotno.Text = ""
            txtBagWt.Text = "0"
            txtWt.Text = "0.000000"
            txtWtrangefrom.Text = "0.000000"
            txtWtrangeto.Text = "0.000000"
            txtTotalNeeded.Text = "0.000000"
            txtRemainingQty.Text = "0.000000"
            Dim prodcode = "(02)" + itemkey + "(10)" + lotno
            Dim mrecx = SearchData(itemkey)

            If mrecx = True Then
                mfrm.mydict.Add("RunNo", txtRunNo.Text)
                mfrm.mydict.Add("FormulaID", txtFGitemkey.Text)
                mfrm.mydict.Add("BatchSize", txtfgsize.Text)
                mfrm.mydict.Add("Batch", txtBatches.Text)
                mfrm.mydict.Add("batchno", txtBatchno.Text)
                mfrm.mydict.Add("prodcode", prodcode)
                mfrm.mydict.Add("allergen", prodcode)
                mfrm.mydict.Add("itemkey", itemkey)
                mfrm.mydict.Add("lotno", lotno)
                mfrm.mydict.Add("BinNo", selbinno)
                txtlotno.Text = lotno
                txtItemKey.Text = itemkey
                mfrm.myscale = ComboBox1.Text
                mfrm.ShowDialog()

            End If



        Else
            MsgBox("Invalid Input", MsgBoxStyle.Critical)

        End If



    End Sub

    Private Sub frmPreweigh_Load(sender As Object, e As EventArgs) Handles Me.Load
        ComboBox1.SelectedIndex = 0
        cmbAllergen.SelectedIndex = 0
    End Sub

    Private Sub txtItemKey_GotFocus(sender As Object, e As EventArgs) Handles txtItemKey.GotFocus
        txtItemKey.Clear()
    End Sub

    Private Sub txtRunNo_KeyPress(sender As Object, e As KeyPressEventArgs) Handles txtRunNo.KeyPress
        If e.KeyChar = vbCr Then
            ' Add the digit to the scan buffer
            ProcessScanRuno(txtRunNo.Text)
        End If
    End Sub

    Private Sub ProcessScanRuno(data As String)
        txtRunNo.Text = data
        btnSearchRunNo.PerformClick()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        If frmFetchWt.serialPort.IsOpen() Then
            frmFetchWt.serialPort.Close()
        End If
    End Sub

    Private Sub cmbAllergen_SelectedIndexChanged(sender As Object, e As EventArgs) Handles cmbAllergen.SelectedIndexChanged
        setmydata(frmdatas)
    End Sub

    Private Sub dgvtopick_CellContentClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgvtopick.CellContentClick

    End Sub

    Private Sub dgvtopick_CellFormatting(sender As Object, e As DataGridViewCellFormattingEventArgs) Handles dgvtopick.CellFormatting

        For i As Integer = 0 To Me.dgvtopick.Rows.Count - 1
            If Me.dgvtopick.Rows(i).Cells("nBulk").Value <> 1 And Me.dgvtopick.Rows(i).Cells("nBulk").Value <> 2 Then
                '   Me.dgvtopick.Rows(i).Style.ForeColor = Color.Red
                dgvtopick.Rows(i).DefaultCellStyle.BackColor = Color.Red
                For columnIndex As Integer = 0 To dgvtopick.Columns.Count - 1
                    Dim tooltiptxt = ""
                    Select Case Me.dgvtopick.Rows(i).Cells("nBulk").Value.ToString
                        Case "3"
                            tooltiptxt = "Bulk Needed"
                        Case "4"
                            tooltiptxt = "No Pack size Found"
                    End Select

                    dgvtopick.Rows(i).Cells(columnIndex).ToolTipText = tooltiptxt
                Next
            End If


        Next

    End Sub


    Private Function SearchData(searchText As String) As Boolean
        ' Clear previous selections
        dgvtopick.ClearSelection()

        ' Iterate through each row in the DataGridView
        For Each row As DataGridViewRow In dgvtopick.Rows

            Dim dta_item As String = row.Cells("item").Value.ToString()
            Dim dta_batchno As String = row.Cells("batchno").Value.ToString()
            Dim dta_nbulk As String = row.Cells("nBulk").Value.ToString()


            If dta_batchno.Contains(txtBatchno.Text) And dta_item.Contains(searchText) Then
                dgindex = row.Index()

                dgvtopick.Rows(dgindex).Selected = True
                Dim e As New DataGridViewCellEventArgs(0, dgindex)
                dgvtopick_CellClick(dgvtopick, e)

                Select Case dta_nbulk
                    Case "1"
                        Return True
                    Case "2"
                        Return True
                    Case "3"
                        MsgBox("Bulk Needed")
                        Return False
                    Case "4"
                        MsgBox("No Pack size Found")
                        Return False
                End Select


            End If

        Next
        MsgBox("No Record Found!")
        Return False
    End Function

    Private Sub btnlotno_Click(sender As Object, e As EventArgs) Handles btnlotno.Click
        ' ProcessScan(txtItemKey.Text, txtlotno.Text)
        selitemkey = ""
        sellotno = ""
        selbinno = ""

        Dim mfrm As New frmLot
        mfrm.ShowDialog()

        If selitemkey <> "" And sellotno <> "" Then
            ProcessScan(selitemkey, sellotno, selbinno)
        End If

    End Sub

    Private Sub txtlotno_KeyPress(sender As Object, e As KeyPressEventArgs) Handles txtlotno.KeyPress
        If e.KeyChar = vbCr Then
            ' Add the digit to the scan buffer
            'ProcessScan(txtItemKey.Text)

            selitemkey = ""
            sellotno = ""
            selbinno = ""

            Dim mfrm As New frmLot
            mfrm.ShowDialog()

            If selitemkey <> "" And sellotno <> "" Then
                ProcessScan(selitemkey, sellotno, selbinno)
            End If

        End If
    End Sub

    Private Sub btnprintrun_Click(sender As Object, e As EventArgs) Handles btnprintrun.Click
        Dim mfrm As New RptRunFrm
        mfrm.myrunno = txtRunNo.Text
        mfrm.ShowDialog()
    End Sub

    Private Sub btnprintbatch_Click(sender As Object, e As EventArgs) Handles btnprintbatch.Click
        Dim mfrm As New RptRunFrm
        mfrm.myrunno = txtBatchno.Text
        mfrm.ShowDialog()
    End Sub

    Private Sub dgpickedlist_CellContentClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgpickedlist.CellContentClick

    End Sub
End Class