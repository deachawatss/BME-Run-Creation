Imports Newtonsoft.Json.Linq

Public Class frmPartialPick
    Dim rs As New Resizer
    Public mydb As New DataGridView
    Private scanBuffer As String = String.Empty
    Public searchby As String = String.Empty
    Public myformulaid As String = String.Empty
    Public gbatchlist As String = String.Empty
    Public batchdic As Dictionary(Of String, Integer) = Nothing



    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        searchby = "runno"
        frmRunModal.ShowDialog()
    End Sub

    Private Sub dgvpicked_CellContentClick(sender As Object, e As DataGridViewCellEventArgs)

    End Sub

    Private Sub dgvtopick_CellContentClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgvtopick.CellClick

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
            Dim wtrangefrom = Math.Round(row.Cells("wtrangefrom").Value, 6, MidpointRounding.AwayFromZero) 'Math.Round((mPartial - (mPartial * wtfrom1p)), 6, MidpointRounding.AwayFromZero)
            Dim wtrangeto = Math.Round(row.Cells("wtrangeto").Value, 6, MidpointRounding.AwayFromZero)
            Dim desc = row.Cells("desc").Value
            Dim mysuggestedData = getSuggested(row.Cells("item").Value)

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
            If mysuggestedData.TryGetValue("lotno", mysuggested) Then
                txtsuggestedlot.Text = mysuggested

            Else
                txtsuggestedlot.Text = ""
            End If


        End If

    End Sub

    Private Sub dgvpicked_CellContentClick_1(sender As Object, e As DataGridViewCellEventArgs) Handles dgvpicked.CellClick

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
            Dim wtrangefrom = Math.Round(row.Cells("pwtrangefrom").Value, 6, MidpointRounding.AwayFromZero)
            Dim wtrangeto = Math.Round(row.Cells("pwtrangeto").Value, 6, MidpointRounding.AwayFromZero)
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

        End If

    End Sub

    Private Sub Button8_Click(sender As Object, e As EventArgs) Handles Button8.Click
        frmFetchWt.ShowDialog()

    End Sub

    Public Sub setmydata(ByVal mydata As Dictionary(Of String, String))
        Dim bmedb As New MsSQL()
        Dim nwfdb As New MySQL()
        dgvpicked.Rows.Clear()
        dgvtopick.Rows.Clear()
        dgpickedlist.Rows.Clear()


        'Access the data in the cells of the row
        Dim RunNo As Integer = mydata("RunNo")
        Dim FormulaID As String = mydata("FormulaID")
        Dim BatchSize As String = mydata("BatchSize")
        Dim Batch As String = mydata("Batch")
        Dim BatchList As String = gbatchlist

        'Try
        'to picked
        Dim topicksql_pm = String.Format("
                    SELECT TOP 1 PT.ItemKey,
                                PM.FormulaId,
                                PT.LineTyp,
                                IM.Desc1,
                                PT.Location,
                                PM.SchStartDate
                    FROM
                                PNMAST PM
                    INNER JOIN PNITEM PT ON PM.BatchNo = PT.BatchNo
                    INNER JOIN INMAST IM ON IM.Itemkey = PT.itemkey
                    
                    WHERE
                    PM.User2 = '{0}' and LineTyp = 'FG' ", RunNo)

        Dim topickpm = bmedb.SelectDataScalar(topicksql_pm)


        'FG Details
        txtfgsize.Text = BatchSize
        txtFGitemkey.Text = topickpm("ItemKey").ToString
        txtFGDesc.Text = topickpm("Desc1").ToString
        txtRunNo.Text = RunNo
        txtBatches.Text = Batch
        myformulaid = topickpm("FormulaId").ToString
        txtproddate.Text = topickpm("SchStartDate").ToString

        'Rawmat Details
        Dim topicksql_rawmat = String.Format("
                        SELECT 
                            DISTINCT
	                            v_prod_assembly.ItemKey,
	                            FormulaId,
	                            v_prod_assembly.Desc1,
	                            StdQtyDispUom,
	                            featurevalue,
	                            PartialData,
	                            ""Bulk"",
	                            BatchNo,
	                            User2,
	                            wtfrom,
	                            wtto,
	                            BatchTicketDate,
                                allergen
                        from v_prod_assembly 
                        left join v_item_allergen on  v_prod_assembly.ItemKey = v_item_allergen.Itemkey
                        where User2 = '{0}' AND LineTyp = 'FI' 
                ", RunNo)


        Dim topickrawmat = bmedb.SelectData(topicksql_rawmat)

        'Picked Summary
        Dim pickedsql = String.Format("
                           Select 
                                runno,
                                itemkey,
                                sum(qty) as qty, 
                                sum(qtybulk) as qtybulk, 
                                sum(qtypartial) as qtypartial, 
                                GROUP_CONCAT(lotno) as lotno ,

                                batchno
                                    from v_allpicked 
                                where 
                                runno = '{0}' and batchno in ('{1}') group by itemkey,batchno
                    ", RunNo, BatchList)

        Dim pickeddata = nwfdb.SelectData(pickedsql)


        Dim mPartial As Double = 0
        Dim itemexist As Boolean = False
        Dim wtfrom1p As Double
        Dim wtfrom As Double
        Dim wtto1p As Double
        Dim wtto As Double

        For Each myitems As JObject In topickrawmat
            itemexist = False
            Dim mpunit As String = myitems("packunit")
            Dim exunit As Boolean = False

            ' Check if packunit is Tote or empty use StdQtyDisUom
            'Select Case UCase(mpunit)
            'Case UCase("tote")
            'mPartial = CDbl(myitems("StdQtyDispUom"))
            'exunit = True
            'Case ""
            'mPartial = CDbl(myitems("StdQtyDispUom"))
            'exunit = True
            'Case Else
            'mPartial = CDbl(myitems("PartialData"))
            'End Select


            If Double.TryParse(myitems("PartialData"), mPartial) Then
                mPartial = Math.Round(CDbl(myitems("PartialData")), 6)

            Else
                mPartial = Math.Round(CDbl(myitems("StdQtyDispUom")), 6)
            End If

            If Double.TryParse(myitems("wtfrom"), wtfrom1p) Then
                wtfrom1p = CDbl(myitems("wtfrom").ToString)
            Else
                wtfrom1p = 0.00075
            End If
            If Double.TryParse(myitems("wtto"), wtto1p) Then
                wtto1p = CDbl(myitems("wtto").ToString)
            Else
                wtto1p = 0.00075
            End If

            wtfrom = Math.Round((mPartial - (mPartial * wtfrom1p)), 6, MidpointRounding.AwayFromZero)
            wtto = Math.Round((mPartial + (mPartial * wtto1p)), 6, MidpointRounding.AwayFromZero)

            'Check if item key required to be kgs
            Dim runinkgs_sql = String.Format("
                           Select 
                               * from tbl_rm_kgs 
                                where 
                                itemkey = '{0}'
                    ", myitems("ItemKey").ToString)

            Dim runinkgs = nwfdb.SelectDataScalar(runinkgs_sql)


            For Each item As JObject In pickeddata
                If item("itemkey").ToString = myitems("ItemKey").ToString And item("batchno").ToString = myitems("BatchNo").ToString Then

                    'Check if Have excess

                    Dim totalneeded As Double = 0
                    Dim pickedqty As Double = 0
                    Dim pickedbulk As Double = 0
                    Dim pickedpartial As Double = 0
                    Dim minbulk As Double = 0
                    Dim myPartial As Double = 0
                    Dim pbalance As Double = 0

                    If Double.TryParse(myitems("StdQtyDispUom"), totalneeded) Then
                        totalneeded = Math.Round(CDbl(myitems("StdQtyDispUom").ToString), 6)
                    End If

                    If Double.TryParse(item("qty"), pickedqty) Then
                        pickedqty = CDbl(item("qty").ToString)
                    End If

                    If Double.TryParse(myitems("featurevalue"), minbulk) Then
                        minbulk = Math.Round(CDbl(myitems("featurevalue").ToString), 6)
                    End If

                    If Double.TryParse(item("qtybulk"), pickedbulk) Then
                        pickedbulk = Math.Round(CDbl(item("qtybulk").ToString), 6)
                    End If

                    If Double.TryParse(item("qtypartial"), pickedpartial) Then
                        pickedpartial = CDbl(item("qtypartial").ToString)
                    End If


                    myPartial = totalneeded - pickedbulk
                    pbalance = Math.Round(CDbl(myPartial) - CDbl(pickedpartial), 6)

                    'If total picked is less than bulk pack
                    'If myPartial < minbulk Or exunit = True Then
                    If runinkgs.Count <= 0 Then

                        mPartial = Math.Round(mPartial, 6)
                        'If total picked is not negative

                        If mPartial > 0 Then

                            If Double.TryParse(myitems("wtfrom"), wtfrom1p) Then
                                wtfrom1p = CDbl(myitems("wtfrom").ToString)
                            Else
                                wtfrom1p = 0.00075
                            End If


                            If Double.TryParse(myitems("wtto"), wtto1p) Then
                                wtto1p = CDbl(myitems("wtto").ToString)
                            Else
                                wtto1p = 0.00075
                            End If

                            wtfrom = Math.Round((mPartial - (mPartial * wtfrom1p)), 6, MidpointRounding.AwayFromZero)
                            wtto = Math.Round((mPartial + (mPartial * wtto1p)), 6, MidpointRounding.AwayFromZero)

                            If pickedpartial < wtfrom Then

                                If pbalance > 0 Then
                                    dgvtopick.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, pickedpartial, pbalance, "", "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"), myitems("allergen"))
                                End If

                            End If



                        End If

                    Else
                        If myPartial < minbulk Or exunit = True Then
                            If myPartial > 0 Then

                                mPartial = Math.Round(myPartial, 6)
                                If Double.TryParse(myitems("wtfrom"), wtfrom1p) Then
                                    wtfrom1p = CDbl(myitems("wtfrom").ToString)
                                Else
                                    wtfrom1p = 0.00075
                                End If


                                If Double.TryParse(myitems("wtto"), wtto1p) Then
                                    wtto1p = CDbl(myitems("wtto").ToString)
                                Else
                                    wtto1p = 0.00075
                                End If

                                wtfrom = Math.Round((mPartial - (mPartial * wtfrom1p)), 6, MidpointRounding.AwayFromZero)
                                wtto = Math.Round((mPartial + (mPartial * wtto1p)), 6, MidpointRounding.AwayFromZero)


                                If pickedpartial < wtfrom Then
                                    dgvtopick.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, pickedpartial, pbalance, "", "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"), myitems("allergen"))
                                    'Else

                                End If



                            End If

                        End If
                    End If

                    If pickedpartial > 0 Then
                        If pbalance < 0 Then
                            pbalance = 0
                        End If

                        If pbalance > minbulk Then
                            pbalance = 0
                        End If
                        dgvpicked.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, pickedpartial, pbalance, item("lotno"), "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"))
                    End If


                    itemexist = True

                End If
            Next

            If itemexist <> True Then

                'Enter code if bulk is not yet encoded

                'If myitems("Bulk") Is Nothing Or myitems("Bulk").ToString = "0" Then
                If runinkgs.Count <= 0 And mPartial > 0 Then
                    dgvtopick.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, "0", mPartial, "", "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"), myitems("allergen"))
                End If
            End If
        Next

        'End If

        'Picked List
        pickedsql = String.Format("
                            SELECT
	                            itemkey,
                                batchno,
                                qty,
                                lotno,
                                tbl_rm_allocate_partial_id
                            FROM
	                            tbl_rm_allocate_partial
                            WHERE
	                            runno = '{0}' and batchno in ('{1}')
                    ", RunNo, BatchList)

        pickeddata = nwfdb.SelectData(pickedsql)

        If pickeddata IsNot Nothing Then
            For Each item As JObject In pickeddata
                dgpickedlist.Rows.Add(item("itemkey"), item("batchno"), item("lotno"), item("qty"), item("tbl_rm_allocate_partial_id"))
            Next

        End If

        Dim found As Boolean = False
        Dim mybatch As String = ""
        For Each row As DataGridViewRow In dgvtopick.Rows
            Dim key As String = row.Cells("item").Value.ToString() ' Replace "KeyColumn" with the actual name of the key column in your DataGridView
            Dim bb As String = row.Cells("batchno").Value.ToString()
            If key = txtItemKey.Text Then
                mybatch = bb
                found = True
                Exit For
            End If
        Next

        If found Then
            txtBatchno.Text = mybatch
        Else
            Try
                txtBatchno.Text = dgvtopick.Rows(0).Cells("batchno").Value.ToString()
            Catch ex As Exception

            End Try
        End If

        ' Catch ex As Exception
        '    LogError(ex)

        'End Try

    End Sub

    Public Sub setmydatac(ByVal mydata As Dictionary(Of String, String))
        Dim bmedb As New MsSQL()
        Dim nwfdb As New MySQL()
        dgvpicked.Rows.Clear()
        dgvtopick.Rows.Clear()
        dgpickedlist.Rows.Clear()


        'Access the data in the cells of the row
        Dim RunNo As Integer = mydata("RunNo")
        Dim FormulaID As String = mydata("FormulaID")
        Dim BatchSize As String = mydata("BatchSize")
        Dim Batch As String = mydata("Batch")
        Dim BatchList As String = gbatchlist


        'FG Details
        Dim qfgdetails = String.Format("
                    SELECT TOP 1 PT.ItemKey,
                                PM.FormulaId,
                                PT.LineTyp,
                                IM.Desc1,
                                PT.Location,
                                PM.SchStartDate
                    FROM
                                PNMAST PM
                    INNER JOIN PNITEM PT ON PM.BatchNo = PT.BatchNo
                    INNER JOIN INMAST IM ON IM.Itemkey = PT.itemkey
                    
                    WHERE
                    PM.User2 = '{0}' and LineTyp = 'FG' ", RunNo)

        Dim fgdetails = bmedb.SelectDataScalar(qfgdetails)

        txtfgsize.Text = BatchSize
        txtFGitemkey.Text = fgdetails("ItemKey").ToString
        txtFGDesc.Text = fgdetails("Desc1").ToString
        txtRunNo.Text = RunNo
        txtBatches.Text = Batch
        myformulaid = fgdetails("FormulaId").ToString
        txtproddate.Text = fgdetails("SchStartDate").ToString

        'Rawmat to pick
        Dim topicksql_rawmat = String.Format("
                        SELECT
	                    pni.BatchNo,
	                    pni.ItemKey,
	                    pni.StdQtyDispUom,
	                    pnm.User2 as ""RunNo""
                    FROM
	                    PNITEM pni
                    LEFT JOIN PNMAST pnm ON pnm.BatchNo = pni.BatchNo
                    WHERE
	                    pnm.User2 = '{0}' and pni.ItemKey is not Null
                ", RunNo)

        Dim topickrawmat = bmedb.SelectData(topicksql_rawmat)

        'Picked Summary
        Dim pickedsql = String.Format("
                           Select 
                                runno,
                                itemkey,
                                sum(qty) as qty, 
                                sum(qtybulk) as qtybulk, 
                                sum(qtypartial) as qtypartial, 
                                GROUP_CONCAT(lotno) as lotno ,
                                batchno
                                    from v_allpicked 
                                where 
                                runno = '{0}' and batchno in ('{1}') group by itemkey,batchno
                    ", RunNo, BatchList)

        Dim pickeddata = nwfdb.SelectData(pickedsql)

        Dim mPartial As Double = 0
        Dim itemexist As Boolean = False
        Dim wtfrom1p As Double
        Dim wtfrom As Double
        Dim wtto1p As Double
        Dim wtto As Double

        For Each myitems As JObject In topickrawmat

            Dim tpBatchNo = ""
            Dim tpItemKey = ""
            Dim tpStdQtyDispUom = ""
            Dim tpRunNo = ""

            myitems.TryGetValue("BatchNo", tpBatchNo)
            myitems.TryGetValue("ItemKey", tpItemKey)
            myitems.TryGetValue("StdQtyDispUom", tpStdQtyDispUom)
            myitems.TryGetValue("RunNo", tpRunNo)

            ''Get v_product_assembly
            'Dim qrminfo = String.Format("
            '              SELECT
            '             vpa.FormulaId,
            '             vpa.ItemKey,
            '             vpa.Desc1,
            '             vpa.StdQtyDispUom,
            '             vpa.featurevalue,
            '             vpa.PartialData,
            '             ""Bulk"",
            '             vpa.BatchNo,
            '             vpa.User2,
            '             vpa.wtfrom,
            '             vpa.wtto,
            '             vpa.BatchTicketDate,
            '             via.allergen
            '            FROM
            '             v_prod_assembly vpa
            '            inner JOIN LotMaster lm on lm.ItemKey = vpa.ItemKey
            '            left join v_item_allergen via on  vpa.ItemKey = via.Itemkey
            '            left join BINMaster bm on  bm.BinNo = lm.BinNo
            '            WHERE
            '             vpa.ItemKey = '{0}' and
            '                vpa.BatchNo = '{1}' and
            '                bm.user4 = 'PARTIAL'
            '        ", tpItemKey, tpBatchNo)

            'Dim rminfo = bmedb.SelectData(qrminfo)

            'If rminfo.Count > 0 Then

            'End If

            'check inventory
            'Dim qinventory = String.Format()

            'dgvtopick.Rows.DefaultCellStyle.BackColor = Color.Red; 
            'Me.DataGridView1.Rows(i).Cells("Quantity:").Style.ForeColor = Color.Red



            dgvtopick.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), "", "", "", "", "", "", "", "", "", "")
        Next

    End Sub


    Public Sub setmydatabatch(ByVal mydata As Dictionary(Of String, String))
        Dim bmedb As New MsSQL()
        Dim nwfdb As New MySQL()
        dgvpicked.Rows.Clear()
        dgvtopick.Rows.Clear()
        dgpickedlist.Rows.Clear()
        ' access the data in the cells of the row
        Dim RunNo As Integer = mydata("RunNo")
        Dim FormulaID As String = mydata("FormulaID")
        Dim BatchSize As String = mydata("BatchSize")
        Dim Batch As String = mydata("Batch")
        Dim batchno As String = mydata("batchno")

        'to picked
        Dim topicksql_pm = String.Format("
                    SELECT TOP 1 PT.ItemKey,
                                PM.FormulaId,
                                PT.LineTyp,
                                IM.Desc1,
                                PT.Location,
                                PM.SchStartDate
                    FROM
                                PNMAST PM
                    INNER JOIN PNITEM PT ON PM.BatchNo = PT.BatchNo
                    INNER JOIN INMAST IM ON IM.Itemkey = PT.itemkey
                    
                    WHERE
                    PM.User2 = '{0}' and LineTyp = 'FG' and PT.BatchNo = '{1}'", RunNo, batchno)

        Dim topickpm = bmedb.SelectDataScalar(topicksql_pm)


        'FG Details


        txtfgsize.Text = BatchSize
        txtFGitemkey.Text = topickpm("ItemKey").ToString
        txtFGDesc.Text = topickpm("Desc1").ToString
        txtRunNo.Text = RunNo
        txtBatches.Text = Batch
        myformulaid = topickpm("FormulaId").ToString
        txtproddate.Text = topickpm("SchStartDate").ToString
        'Rawmat Details
        Dim topicksql_rawmat = String.Format("
                        SELECT 
                            DISTINCT
	                            ItemKey,
	                            FormulaId,
	                            Desc1,
	                            StdQtyDispUom,
	                            featurevalue,
	                            PartialData,
	                            ""Bulk"",
	                            BatchNo,
	                            User2,
	                            wtfrom,
	                            wtto,
	                            BatchTicketDate
                        from v_prod_assembly where User2 = '{0}' AND LineTyp = 'FI'  and BatchNo = '{1}'
                ", RunNo, batchno)

        Dim topickrawmat = bmedb.SelectData(topicksql_rawmat)

        'Picked Summary
        Dim pickedsql = String.Format("
                           Select 
                                runno,
                                itemkey,
                                sum(qty) as qty, 
                                sum(qtybulk) as qtybulk, 
                                sum(qtypartial) as qtypartial, 
                                GROUP_CONCAT(lotno) as lotno ,
                                batchno
                                    from v_allpicked 
                                where 
                                runno = '{0}' and batchno = '{1}' group by itemkey,batchno
                    ", RunNo, batchno)

        Dim pickeddata = nwfdb.SelectData(pickedsql)

        Dim mPartial As Double = 0
        Dim itemexist As Boolean = False
        Dim wtfrom1p As Double
        Dim wtfrom As Double
        Dim wtto1p As Double
        Dim wtto As Double

        For Each myitems As JObject In topickrawmat
            itemexist = False
            Dim mpunit As String = myitems("packunit")
            Dim exunit As Boolean = False

            ' Check if packunit is Tote or empty use StdQtyDisUom
            'Select Case UCase(mpunit)
            'Case UCase("tote")
            'mPartial = CDbl(myitems("StdQtyDispUom"))
            'exunit = True
            'Case ""
            'mPartial = CDbl(myitems("StdQtyDispUom"))
            'exunit = True
            'Case Else
            'mPartial = CDbl(myitems("PartialData"))
            'End Select

            If Double.TryParse(myitems("PartialData"), mPartial) Then
                mPartial = Math.Round(CDbl(myitems("PartialData")), 6)
            Else
                mPartial = Math.Round(CDbl(myitems("StdQtyDispUom")), 6)

            End If


            If Double.TryParse(myitems("wtfrom"), wtfrom1p) Then
                wtfrom1p = CDbl(myitems("wtfrom").ToString)
            Else
                wtfrom1p = 0.00075
            End If
            If Double.TryParse(myitems("wtto"), wtto1p) Then
                wtto1p = CDbl(myitems("wtto").ToString)
            Else
                wtto1p = 0.00075
            End If

            wtfrom = (mPartial - (mPartial * wtfrom1p))
            wtto = (mPartial + (mPartial * wtto1p))

            'Check if item key required to be kgs
            Dim runinkgs_sql = String.Format("
                           Select 
                               * from tbl_rm_kgs 
                                where 
                                itemkey = '{0}'
                    ", myitems("ItemKey").ToString)

            Dim runinkgs = nwfdb.SelectDataScalar(runinkgs_sql)


            For Each item As JObject In pickeddata
                If item("itemkey").ToString = myitems("ItemKey").ToString And item("batchno").ToString = myitems("BatchNo").ToString Then

                    'Check if Have excess

                    Dim totalneeded As Double = 0
                    Dim pickedqty As Double = 0
                    Dim pickedbulk As Double = 0
                    Dim pickedpartial As Double = 0
                    Dim minbulk As Double = 0
                    Dim myPartial As Double = 0
                    Dim pbalance As Double = 0

                    If Double.TryParse(myitems("StdQtyDispUom"), totalneeded) Then
                        totalneeded = CDbl(myitems("StdQtyDispUom").ToString)
                    End If

                    If Double.TryParse(item("qty"), pickedqty) Then
                        pickedqty = CDbl(item("qty").ToString)
                    End If

                    If Double.TryParse(myitems("featurevalue"), minbulk) Then
                        minbulk = CDbl(myitems("featurevalue").ToString)
                    End If

                    If Double.TryParse(item("qtybulk"), pickedbulk) Then
                        pickedbulk = CDbl(item("qtybulk").ToString)
                    End If

                    If Double.TryParse(item("qtypartial"), pickedpartial) Then
                        pickedpartial = CDbl(item("qtypartial").ToString)
                    End If




                    myPartial = totalneeded - pickedbulk
                    pbalance = Math.Round(CDbl(myPartial) - CDbl(pickedpartial), 6)

                    If runinkgs.Count <= 0 Then
                        mPartial = Math.Round(mPartial, 6)
                        'If total picked is not negative

                        If mPartial > 0 Then

                            If Double.TryParse(myitems("wtfrom"), wtfrom1p) Then
                                wtfrom1p = CDbl(myitems("wtfrom").ToString)
                            Else
                                wtfrom1p = 0.00075
                            End If


                            If Double.TryParse(myitems("wtto"), wtto1p) Then
                                wtto1p = CDbl(myitems("wtto").ToString)
                            Else
                                wtto1p = 0.00075
                            End If

                            wtfrom = (mPartial - (mPartial * wtfrom1p))
                            wtto = (mPartial + (mPartial * wtto1p))


                            If pickedpartial < wtfrom Then
                                dgvtopick.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, pickedpartial, pbalance, "", "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"))
                                'Else

                            End If



                        End If


                    Else
                        'If total picked is less than bulk pack
                        If myPartial < minbulk Or exunit = True Then
                            'If total picked is not negative
                            If myPartial > 0 Then

                                mPartial = Math.Round(myPartial, 6)
                                If Double.TryParse(myitems("wtfrom"), wtfrom1p) Then
                                    wtfrom1p = CDbl(myitems("wtfrom").ToString)
                                Else
                                    wtfrom1p = 0.00075
                                End If


                                If Double.TryParse(myitems("wtto"), wtto1p) Then
                                    wtto1p = CDbl(myitems("wtto").ToString)
                                Else
                                    wtto1p = 0.00075
                                End If

                                wtfrom = (mPartial - (mPartial * wtfrom1p))
                                wtto = (mPartial + (mPartial * wtto1p))


                                If pickedpartial < wtfrom Then
                                    dgvtopick.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, pickedpartial, pbalance, "", "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"))
                                    'Else

                                End If



                            End If


                        End If

                    End If

                    If pickedpartial > 0 Then
                        dgvpicked.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, pickedpartial, pbalance, item("lotno"), "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"))
                    End If


                    itemexist = True

                End If
            Next
            If itemexist <> True Then
                ' If myitems("Bulk") Is Nothing Or myitems("Bulk").ToString = "0" Then
                If runinkgs.Count <= 0 And mPartial > 0 Then
                    dgvtopick.Rows.Add(myitems("ItemKey"), myitems("BatchNo"), mPartial, "0", mPartial, "", "", myitems("featurevalue"), wtfrom, wtto, myitems("Desc1"))

                End If

            End If
        Next


        'For Each item As JObject In topickrawmat
        '    Dim mPartial As Double
        '    Dim wtfrom1p As Double
        '    Dim wtfrom As Double
        '    Dim wtto1p As Double
        '    Dim wtto As Double
        '    Dim mmPartial As Double
        '   Dim mpunit As String = item("packunit")


        '    Select Case UCase(mpunit)
        '        Case UCase("tote")
        '            'Rawmat 
        '            If Double.TryParse(item("StdQtyDispUom"), mPartial) Then
        '                mPartial = Math.Round(CDbl(item("StdQtyDispUom")), 6)

        '                wtfrom1p = (CDbl(item("StdQtyDispUom").ToString) - (CDbl(item("StdQtyDispUom").ToString) * 0.00075))
        '                wtfrom = If(item("wtfrom").ToString = Nothing, wtfrom1p, item("wtfrom"))

        '                wtto1p = (CDbl(item("StdQtyDispUom").ToString) + (CDbl(item("StdQtyDispUom").ToString) * 0.00075))
        '                wtto = If(item("wtto").ToString = Nothing, wtto1p, item("wtto"))

        '            Else
        '                mPartial = 0
        '            End If

        '        Case ""
        '            'Rawmat 
        '            If Double.TryParse(item("StdQtyDispUom"), mPartial) Then
        '                mPartial = Math.Round(CDbl(item("StdQtyDispUom")), 6)

        '                wtfrom1p = (CDbl(item("StdQtyDispUom").ToString) - (CDbl(item("StdQtyDispUom").ToString) * 0.00075))
        '                wtfrom = If(item("wtfrom").ToString = Nothing, wtfrom1p, item("wtfrom"))

        '                wtto1p = (CDbl(item("StdQtyDispUom").ToString) + (CDbl(item("StdQtyDispUom").ToString) * 0.00075))
        '                wtto = If(item("wtto").ToString = Nothing, wtto1p, item("wtto"))

        '            Else
        '                mPartial = 0
        '            End If

        '        Case Else
        '            'Rawmat 
        '            If Double.TryParse(item("PartialData"), mPartial) Then
        '                mPartial = Math.Round(CDbl(item("PartialData")), 6)

        '                wtfrom1p = (CDbl(item("PartialData").ToString) - (CDbl(item("PartialData").ToString) * 0.00075))
        '                wtfrom = If(item("wtfrom").ToString = Nothing, wtfrom1p, item("wtfrom"))

        '                wtto1p = (CDbl(item("PartialData").ToString) + (CDbl(item("PartialData").ToString) * 0.00075))
        '                wtto = If(item("wtto").ToString = Nothing, wtto1p, item("wtto"))

        '            Else
        '                mPartial = 0
        '            End If
        '    End Select


        '    If mPartial <> 0 Then

        '        Dim pickedsql = String.Format("
        '                   Select runno,itemkey,sum(qty) as qty, GROUP_CONCAT(lotno) as lotno from tbl_rm_allocate_partial where runno = '{0}' and itemkey = '{1}' and batchno = '{2}' group by itemkey,batchno
        '            ", RunNo, item("ItemKey"), item("BatchNo"))
        '        Dim pickeddata As Dictionary(Of String, Object) = nwfdb.SelectDataScalar(pickedsql)

        '        If pickeddata.Count > 0 Then

        '            If pickeddata("qty") >= wtfrom And pickeddata("qty") <= wtto Then
        '                Dim mybal = CDbl(mPartial) - CDbl(pickeddata("qty"))
        '                dgvpicked.Rows.Add(item("ItemKey"), item("BatchNo"), mPartial, pickeddata("qty"), mybal.ToString("######.######"), pickeddata("lotno"), "", item("featurevalue"), "0.000000", "0.000000", item("Desc1"))
        '            Else
        '                Dim mybal = CDbl(mPartial) - CDbl(pickeddata("qty"))
        '                dgvtopick.Rows.Add(item("ItemKey"), item("BatchNo"), mPartial, pickeddata("qty"), mybal.ToString("######.######"), pickeddata("lotno"), "", item("featurevalue"), "0.000000", "0.000000", item("Desc1"))

        '            End If
        '        Else
        '            dgvtopick.Rows.Add(item("ItemKey"), item("BatchNo"), mPartial, "0.000000", mPartial, "", "", item("featurevalue"), "0.000000", "0.000000", item("Desc1"))

        '        End If


        '    End If


        'Next

        'PickedList
        Dim mypickedsql = String.Format("
                SELECT
	                itemkey,
                    batchno,
                    qty,
                    lotno,
                    tbl_rm_allocate_partial_id
                FROM
	                tbl_rm_allocate_partial
                WHERE
	                runno = '{0}' and batchno = '{1}'
            ", RunNo, batchno)

        Dim mypickdata = nwfdb.SelectData(mypickedsql)
        For Each item As JObject In mypickdata
            dgpickedlist.Rows.Add(item("itemkey"), item("batchno"), item("lotno"), item("qty"), item("tbl_rm_allocate_partial_id"))
        Next
    End Sub


    Private Sub frmPartialPick_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim mydict As New Dictionary(Of String, String)
        mydict.Add("", "")
        ComboBox1.SelectedIndex = 0

        'rs.FindAllControls(Me)
    End Sub

    Private Sub Form1_Resize(sender As Object, e As EventArgs) Handles Me.Resize
        rs.ResizeAllControls(Me)
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        searchby = "batchno"
        frmFetchWtBatchList.myrunno = txtRunNo.Text
        frmFetchWtBatchList.mybatchno = txtBatchno.Text
        frmFetchWtBatchList.ShowDialog()
    End Sub

    Private Sub Button3_Click(sender As Object, e As EventArgs)

    End Sub

    Private Sub txtlotno_TextChanged(sender As Object, e As EventArgs)

        Dim str As String = txtlotno.Text
        Dim chkstr As Boolean = str.Contains("(02)")

        If chkstr Then
            Dim mycode = mybarcode(txtlotno.Text)
            txtlotno.Text = mycode.Item("02")
        End If
        '

    End Sub

    Private Sub txtlotno_KeyPress(sender As Object, e As KeyPressEventArgs) Handles txtItemKey.KeyPress
        If e.KeyChar <> vbCr Then
            ' Add the digit to the scan buffer
            scanBuffer &= e.KeyChar
        Else
            ' A carriage return indicates the end of the scan, so process the scanned data
            'ProcessScan(scanBuffer)
            ' Clear the scan buffer
            ProcessScan(txtItemKey.Text)
            scanBuffer = String.Empty
        End If
    End Sub

    Private Sub ProcessScan(data As String)
        ' Do something with the scanned data, for example:
        Dim mycode = mybarcode(data)
        Dim myrec As Dictionary(Of String, String) = Nothing
        Dim nwfdb As New MySQL()
        Dim mfrm As New frmFetchWt
        Dim mrec As Boolean = False

        'Try
        txtDesc.Text = ""
        txtlotno.Text = ""
        txtBagWt.Text = "0"
        txtWt.Text = "0.000000"
        txtWtrangefrom.Text = "0.000000"
        txtWtrangeto.Text = "0.000000"
        txtTotalNeeded.Text = "0.000000"
        txtRemainingQty.Text = "0.000000"



        If txtRunNo.Text Is "" And txtBatchno.Text Is "" Then
            MsgBox("Invalid Run No and Batch No")

        ElseIf txtRunNo.Text Is "" Then
            MsgBox("Invalid Run No")

        ElseIf txtBatchno.Text Is "" Then
            MsgBox("Invalid Batch No")
        ElseIf mycode.ContainsKey("10") = False Then
            MsgBox("Invalid Item Barcode")
        Else

            If mycode.Item("10") = txtsuggestedlot.Text Then
                mrec = True
            Else

                Dim confirm = MsgBox("You picked different Lot than suggested, Do you still want to continue?", MsgBoxStyle.YesNo, "NWF Mobile")

                If confirm = vbYes Then
                    mrec = True
                End If

            End If

            If mrec = True Then
                txtlotno.Text = mycode.Item("10")
                txtItemKey.Text = mycode.Item("02")
                Dim mydata As New Dictionary(Of String, String)
                mydata.Add("RunNo", txtRunNo.Text)
                'mydata.Add("FormulaID", .Text)
                mydata.Add("BatchNo", txtBatchno.Text)
                mydata.Add("ItemKey", mycode.Item("02"))
                mydata.Add("LotNo", mycode.Item("10"))
                myrec = getmydata(mydata)


                If myrec.Count = 0 Then
                    MsgBox("Item key/Lot No not found")
                Else
                    mfrm.txtItemBarcode.Text = "(02)" & mycode.Item("02") & "(10)" & mycode.Item("10")
                    mfrm.txtBatchNo.Text = txtBatchno.Text
                    mfrm.txtRunNo.Text = txtRunNo.Text
                    'Debug.WriteLine(myrec.Item("QtyAvailable"))
                    mfrm.txtstockonhand.Text = Math.Round(CDbl(myrec.Item("QtyAvailable").ToString), 6)

                    txtDesc.Text = myrec.Item("Desc1").ToString
                    txtBagWt.Text = myrec.Item("featurevalue").ToString
                    txtWt.Text = myrec.Item("myPartial").ToString
                    txtWtrangefrom.Text = myrec.Item("wtfrom")
                    txtWtrangeto.Text = myrec.Item("wtto")

                    mfrm.txtwtfr.Text = myrec.Item("wtfrom")
                    mfrm.txtwtto.Text = myrec.Item("wtto")
                    mfrm.txtreqwt.Text = myrec.Item("myPartial").ToString
                    mfrm.myitemkey = mycode.Item("02").ToString
                    mfrm.strlotno = mycode.Item("10").ToString
                    mfrm.Dateexpiry = myrec.Item("Dateexpiry").ToString
                    txtTotalNeeded.Text = myrec.Item("myPartial").ToString
                    myformulaid = myrec.Item("FormulaId").ToString

                    If Double.TryParse(myrec.Item("StdQtyDispUom").ToString, mfrm.StdQtyDispUom) Then
                        mfrm.StdQtyDispUom = CDbl(myrec.Item("StdQtyDispUom").ToString)
                    End If



                    Dim mypickedsql = String.Format("
                SELECT
                    tbl_rm_allocate_partial_id,
	                itemkey,
                    batchno,
                    qty,
                    lotno
                FROM
	                tbl_rm_allocate_partial
                WHERE
	                runno = '{0}' and batchno = '{1}' and itemkey = '{2}'
            ", txtRunNo.Text, txtBatchno.Text, mycode.Item("02"))
                    Dim mqty As Double = 0
                    Dim mypickdata = nwfdb.SelectData(mypickedsql)
                    For Each item As JObject In mypickdata
                        mfrm.DataGridView1.Rows.Add("", item("itemkey"), item("lotno"), "", item("qty"), item("batchno"), txtRunNo.Text, "O", item("tbl_rm_allocate_partial_id"))
                        ' DataGridView1.Rows.Add("", myitemkey, strlotno, Dateexpiry, txtactualwt.Text, txtBatchno.Text, txtRunNo.Text)
                        mqty += CDbl(item("qty"))
                    Next
                    mfrm.changetotal()
                    mfrm.myscale = ComboBox1.Text
                    txtRemainingQty.Text = mqty.ToString("0.000000")
                    mfrm.ShowDialog()
                End If

            End If

        End If




        'Catch ex As Exception 

        'MsgBox("Item key/Lot No not found")
        'End Try


    End Sub

    Private Sub ProcessScanRuno(data As String)
        txtRunNo.Text = data
        Button1.PerformClick()
    End Sub
    Private Sub txtItemKey_GotFocus(sender As Object, e As EventArgs) Handles txtItemKey.GotFocus
        txtItemKey.Clear()
    End Sub


    Private Sub txtRunNo_KeyPress(sender As Object, e As KeyPressEventArgs) Handles txtRunNo.KeyPress
        If e.KeyChar <> vbCr Then
            ' Add the digit to the scan buffer
            scanBuffer &= e.KeyChar
        Else
            ' A carriage return indicates the end of the scan, so process the scanned data
            ProcessScanRuno(scanBuffer)
            ' Clear the scan buffer
            scanBuffer = String.Empty
        End If
    End Sub

    Private Sub dgpickedlist_CellDoubleClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgpickedlist.CellDoubleClick

        If e.RowIndex >= 0 Then
            Dim row As DataGridViewRow = dgpickedlist.Rows(e.RowIndex)
            Dim barcode As String = "(02)" & row.Cells("DataGridViewTextBoxColumn6").Value & "(10)" & row.Cells("DataGridViewTextBoxColumn8").Value
            Dim itemkey As String = row.Cells("DataGridViewTextBoxColumn6").Value
            Dim lotno As String = row.Cells("DataGridViewTextBoxColumn8").Value
            Dim qty As String = row.Cells("DataGridViewTextBoxColumn9").Value
            Dim batch As String = row.Cells("DataGridViewTextBoxColumn7").Value
            Dim mycode As String = row.Cells("partial_id").Value

            Dim zbarcode = "nwf-pb-" & mycode.PadLeft(11, "0")
            Dim mystring As String
            'Dim myreg As New RegistryCRUD()
            Dim msdb As New MsSQL()

            Try
                Dim readDictionary As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
                Dim nwf_printer As Object = readDictionary("nwf_printer")

                Dim allergen
                Dim alq = String.Format("Select * from v_item_allergen where itemkey = '{0}'", itemkey)

                Dim ald = msdb.SelectDataScalar(alq)
                'allergen = ald("allergenx")
                'Debug.WriteLine(ald("alelrgenx"))
                If ald.Count > 0 And ald.TryGetValue("allergen", allergen) Then
                    If IsDBNull(allergen) Then
                        allergen = ""
                    Else
                        allergen = ald("allergen").ToString
                    End If

                Else
                    allergen = ""
                End If

                If nwf_printer IsNot Nothing Then
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
                            ^FT342,79^A0I,39,36^FH\^FDAllergen:^FS
                            ^FT206,79^A0I,39,36^FH\^FD{5}^FS
                            ^PQ1,0,1,Y^XZ

                            ", itemkey, lotno, qty, zbarcode, batch, allergen)

                    BarcodePrinter.SendZplOverUsb(mystring, nwf_printer)

                End If


            Catch ex As Exception
                LogError(ex)
                Debug.WriteLine(ex)
            End Try



        End If
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        If frmFetchWt.serialPort.IsOpen() Then
            frmFetchWt.serialPort.Close()
        End If
    End Sub

    Private Sub dgpickedlist_CellContentClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgpickedlist.CellContentClick

    End Sub

    Private Sub dgvtopick_CellContentClick_1(sender As Object, e As DataGridViewCellEventArgs) Handles dgvtopick.CellContentClick

    End Sub

    Private Sub dgvpicked_CellContentClick_2(sender As Object, e As DataGridViewCellEventArgs) Handles dgvpicked.CellContentClick

    End Sub
End Class