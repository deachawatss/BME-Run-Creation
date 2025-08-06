Imports Newtonsoft.Json.Linq

Public Class frminventory
    Dim bmedb As New MsSQL()
    Private Sub frminventory_Load(sender As Object, e As EventArgs) Handles Me.Load
        txtFilter.SelectedIndex = 0
        dtgInvertory.Rows().Clear()
        Dim q1 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                         left join BINMaster bm on bm.BinNo = lm.BinNo
                    WHERE
                    bm.User4 = 'PARTIAL'
                    AND lm.locationkey = 'MAIN'
                    AND (lm.qtyonhand - lm.Qtycommitsales) > 0
                    and inventory_adjustment is null
                    ")
        Dim q1d = bmedb.SelectData(q1)

        For Each item As JObject In q1d

            If CDbl(item("QtyAvailable")) > 0.05 Then
                dtgInvertory.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
            End If

        Next


        dgvinv.Rows().Clear()
        Dim q2 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                         left join BINMaster bm on bm.BinNo = lm.BinNo
                    WHERE
                    bm.User4 = 'PARTIAL'
                    and lm.transactiontype = '8'
                    AND lm.locationkey = 'MAIN'
                    and inventory_adjustment= 'Y'
                    ")
        Dim q2d = bmedb.SelectData(q2)

        For Each item As JObject In q2d

            'If CDbl(item("QtyAvailable")) > 0.05 Then
            dgvinv.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
            ' End If

        Next

        'dtgInvertory

    End Sub

    Private Sub btnSearch_Click(sender As Object, e As EventArgs) Handles btnSearch.Click
        Dim q1 As String
        Dim q1d As JArray
        dtgInvertory.Rows().Clear()
        If txtFilter.SelectedIndex = 0 Then
            q1 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                          left join BINMaster bm on bm.BinNo = lm.BinNo
                    WHERE
                    bm.User4 = 'PARTIAL'
                    and lm.transactiontype = '8'
                    AND lm.locationkey = 'MAIN'
                    and lm.itemkey = '{0}'
                    and inventory_adjustment is null
                    ", txtSearch.Text)
            q1d = bmedb.SelectData(q1)

            For Each item As JObject In q1d
                ' If CDbl(item("QtyAvailable")) > 0.05 Then
                dtgInvertory.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
                ' End If
            Next
        ElseIf txtFilter.SelectedIndex = 1 Then
            q1 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                         left join BINMaster bm on bm.BinNo = lm.BinNo
                    WHERE
                    bm.User4 = 'PARTIAL'
                    and lm.transactiontype = '8'
                    AND lm.locationkey = 'MAIN'
                    and lm.lotno = '{0}'
                    and inventory_adjustment is null
                    ", txtSearch.Text)
            q1d = bmedb.SelectData(q1)

            For Each item As JObject In q1d
                ' If CDbl(item("QtyAvailable")) > 0.05 Then
                dtgInvertory.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
                ' End If
            Next
        Else
            Dim bcode = mybarcode(txtSearch.Text)
            Dim ikey As String
            Dim lcode As String

            If IsNothing(bcode.Item("02")) = False And IsNothing(bcode.Item("10")) = False Then
                ikey = bcode.Item("02")
                lcode = bcode.Item("10")
                q1 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                         left join BINMaster bm on bm.BinNo = lm.BinNo
                    WHERE
                    bm.User4 = 'PARTIAL'
                    and lm.transactiontype = '8'
                    AND lm.locationkey = 'MAIN'
                    and lm.itemkey = '{0}'
                    and lm.lotno = '{1}'
                    and inventory_adjustment is null
                    ", ikey, lcode)
                q1d = bmedb.SelectData(q1)

                For Each item As JObject In q1d
                    ' If CDbl(item("QtyAvailable")) > 0.05 Then
                    dtgInvertory.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
                    'End If
                Next


            End If


        End If



    End Sub

    Private Sub btnViewAll_Click(sender As Object, e As EventArgs) Handles btnViewAll.Click
        dtgInvertory.Rows().Clear()
        Dim q1 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                    lotmaster lm 
                        left join BINMaster bm on bm.BinNo = lm.BinNo
                    WHERE
                    bm.User4 = 'PARTIAL'
                    and lm.transactiontype = '8'
                    AND lm.locationkey = 'MAIN'
                    and inventory_adjustment is null
                    ")
        Dim q1d = bmedb.SelectData(q1)

        For Each item As JObject In q1d
            ' If CDbl(item("QtyAvailable")) > 0.05 Then
            dtgInvertory.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
            ' End If
        Next
    End Sub

    Private Sub dtgInvertory_CellDoubleClick(sender As Object, e As DataGridViewCellEventArgs) Handles dtgInvertory.CellDoubleClick
        If e.RowIndex >= 0 Then
            Dim row As DataGridViewRow = dtgInvertory.Rows(e.RowIndex)
            Dim barcode As String = "(02)" & row.Cells("ITEMKEY").Value & "(10)" & row.Cells("LOTNO").Value
            Dim itemkey As String = row.Cells("ITEMKEY").Value
            Dim lotno As String = row.Cells("LOTNO").Value
            Dim qty As String = row.Cells("qty_available").Value
            Dim mystring As String
            Dim readDictionary As Dictionary(Of String, Object) = ReadXmlFileToDictionary()
            Dim nwf_printer As Object = readDictionary("nwf_printer")
            If nwf_printer IsNot Nothing Then
                'mystring = String.Format("
                '            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
                '            ^XA
                '            ^MMT
                '            ^PW812
                '            ^LL0203
                '            ^LS0
                '            ^FT12,50^A0N,39,38^FH\^FDItem Key:^FS
                '            ^FT163,50^A0N,39,38^FH\^FD{1}^FS
                '            ^FT537,50^A0N,39,38^FH\^FDQty:^FS
                '            ^FT598,50^A0N,39,45^FH\^FD{2}^FS
                '            ^BY2,3,65^FT173,162^BCN,,Y,N
                '            ^FD>:{0}^FS
                '            ^PQ1,0,1,Y^XZ

                '            ", barcode, itemkey, qty)

                mystring = String.Format("
                            ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD25^JUS^LRN^CI0^XZ
                            ^XA
                            ^MMT
                            ^PW812
                            ^LL0203
                            ^LS0
                            ^FT15,48^A0N,39,50^FH\^FDITEM KEY:^FS
                            ^BY1,6,50^FT150,162^BCN,,Y,N
                            ^FD>:{3}^FS
                            ^FT15,94^A0N,39,50^FH\^FDLOT NO:^FS
                            ^FT440,48^A0N,39,50^FH\^FDQUANTITY:^FS
                            ^FT232,48^A0N,39,48^FH\^FD{0}^FS
                            ^FT183,94^A0N,39,48^FH\^FD{1}^FS
                            ^FT675,48^A0N,39,48^FH\^FD{2}^FS
                            ^PQ1,0,1,Y^XZ
                            ", itemkey, lotno, qty, barcode)

                BarcodePrinter.SendZplOverUsb(mystring, nwf_printer)

            End If
        End If


    End Sub

    Private Sub dtgInvertory_CellContentClick(sender As Object, e As DataGridViewCellEventArgs) Handles dtgInvertory.CellContentClick
        If e.RowIndex >= 0 Then
            Dim dtval = dtgInvertory.Rows(e.RowIndex).Cells(0).Value
            If e.ColumnIndex = 0 Then

                If dtval = True Then
                    dtgInvertory.Rows(e.RowIndex).Cells(0).Value = False
                Else
                    dtgInvertory.Rows(e.RowIndex).Cells(0).Value = True
                End If

            Else

            End If


        End If

    End Sub

    Private Sub btnTransfer_Click(sender As Object, e As EventArgs) Handles btnTransfer.Click
        Dim mydate As String = Now.ToString("MM/dd/yyyy HH:mm")
        Dim myuserinfo = UserInfo.getUserinfo()
        Dim msdb As New MsSQL()
        For Each row As DataGridViewRow In dtgInvertory.Rows
            If row.Cells("chkbtn").Value = True Then

                'check MINTXD
                Dim mintxd_str = String.Format("
                    Select * from mintxd where ItemKey = '{0}' and Location = 'MAIN'
                ", row.Cells("ITEMKEY").Value.ToString())
                Dim mintxd As Dictionary(Of String, Object)
                mintxd = msdb.SelectDataScalar(mintxd_str)

                'Transfer to data
                Dim mdata As New Dictionary(Of String, String)
                'LotTransaction to update/Add data
                Dim ulottrans As New Dictionary(Of String, String)

                'Get LotTransaction
                Dim lottransaction_query = String.Format("
                    SELECT
	                    TOP 1 *
                    FROM
	                    LotTransaction
                        left join BINMaster bm on bm.BinNo = LotTransaction.BinNo
                    WHERE
                    bm.User4 = 'PARTIAL'
	                and LocationKey = 'MAIN'
                    AND lotno = '{0}'
                    AND itemkey = '{1}'
                ", row.Cells("LOTNO").Value.ToString(), row.Cells("ITEMKEY").Value.ToString())

                Dim lottransaction = msdb.SelectDataScalar(lottransaction_query)

                If mintxd.Count() > 0 Then
                    Dim myqty = CDbl(row.Cells("qty_available").Value.ToString()) - CDbl(mintxd("TrnQty"))
                    mdata.Add("TrnQty", myqty) ' - qty
                    mdata.Add("DispTrnQty", myqty) ' - qty
                    msdb.Update("MINTXD", mdata, String.Format("InTransID = {0}", mintxd("InTransID")))

                    'check LotTransaction
                    Dim lottransaction_chk_query = String.Format("
                            SELECT
	                            *
                            FROM
	                            LotTransaction
                                left join BINMaster bm on bm.BinNo = LotTransaction.BinNo
                            WHERE
	                            LocationKey = 'MAIN'
                            AND lotno = '{0}'
                            AND itemkey = '{1}'
                            and bm.User4 = 'PARTIAL'
                            and IssueDocNo = '{2}'
                         ", row.Cells("ITEMKEY").Value.ToString(), row.Cells("LOTNO").Value.ToString(), mintxd("InTransID"))

                    Dim lottransaction_chk = msdb.SelectDataScalar(lottransaction_chk_query)

                    If lottransaction_chk.Count() > 0 Then
                        'To Update LotTransaction
                        Dim qtyissued = CDbl(lottransaction_chk("QtyIssued")) + CDbl(row.Cells("qty_available").Value.ToString())
                        ulottrans.Add("QtyIssued", qtyissued)
                        msdb.Update("LotTransaction", mdata, String.Format("LotTranNo = {0}", lottransaction_chk("LotTranNo")))
                    End If

                Else
                    'get Seqnum
                    Dim seqnum_query = String.Format("
                                        SELECT
	                                        *
                                        FROM
	                                       SeqNum
                                        WHERE
	                                        SeqName = 'OI'
                                        ")

                    Dim seqnum As Dictionary(Of String, Object)
                    seqnum = msdb.SelectDataScalar(seqnum_query)
                    Dim myseqnum = String.Format("OI-{0}", (CInt(seqnum("SeqNum")) + 1))
                    'get details
                    Dim myrec_query = String.Format("
                                        SELECT
	                                        LM.ItemKey,
	                                        IM.Desc1,
	                                        LM.LotNo,
	                                        LM.QtyOnHand,
	                                        LM.DateExpiry,
	                                        LM.BinNo,
	                                        IL.Inclasskey,
	                                        IC.AccrStdCstVarAcct,
	                                        IM.Stockuomcode
                                        FROM
	                                        LotMaster LM
                                        INNER JOIN INMAST IM ON IM.Itemkey = LM.ItemKey
                                        INNER JOIN INLOC IL ON LM.Itemkey = IL.Itemkey
                                        AND LM.LocationKey = IL.Location
                                        INNER JOIN INCLASS IC ON IC.Inclasskey = IL.Inclasskey
                                        left join BINMaster bm on bm.BinNo = lm.BinNo
                                        WHERE
	                                        LM.locationKey = 'MAIN'
                                        AND LM.ItemKey = '{0}'
                                        AND LM.LotNo = '{1}'
                                        AND bm.User4 = 'PARTIAL'
                                        ", row.Cells("ITEMKEY").Value.ToString(), row.Cells("LOTNO").Value.ToString())

                    Dim myrec As Dictionary(Of String, Object)
                    myrec = msdb.SelectDataScalar(myrec_query)

                    mdata.Add("ItemKey", row.Cells("ITEMKEY").Value.ToString())
                    mdata.Add("Location", "MAIN")
                    mdata.Add("ToLocation", "")
                    mdata.Add("SysID", "7") 'row.Cells("Qty").Value.ToString()
                    mdata.Add("SysDocID", myseqnum) 'NEXT SysDocID
                    mdata.Add("SysLinSq", "1")
                    mdata.Add("TrnTyp", "A")
                    mdata.Add("TrnSubTyp", "MA")
                    mdata.Add("DocDate", mydate)
                    mdata.Add("AplDate", mydate)
                    mdata.Add("TrnDesc", "Pre-Weigh Adjustment")
                    mdata.Add("TrnQty", "-" & row.Cells("qty_available").Value.ToString()) ' - qty
                    mdata.Add("TrnAmt", "0")
                    mdata.Add("DispTrnQty", "-" & row.Cells("qty_available").Value.ToString()) ' - qty
                    mdata.Add("DispTrnAmt", "0")
                    mdata.Add("StockUom", myrec("Stockuomcode")) 'INMAST.Stockuomcode
                    mdata.Add("DispUom", myrec("Stockuomcode")) 'INMAST.Stockuomcode
                    mdata.Add("NLAcct", "128614")
                    mdata.Add("INAcct", myrec("AccrStdCstVarAcct")) 'INCLASS.AccrSTDCstVarAcct
                    mdata.Add("CreatedSerlot", "Y")
                    mdata.Add("RecUserID", myuserinfo.Item("uname").ToString)
                    mdata.Add("RecDate", mydate)
                    mdata.Add("Updated_FinTable", "0")
                    mdata.Add("QtySerLot", "0")
                    mdata.Add("User6", mydate)
                    msdb.Create("MINTXD", mdata)

                    'Add lottransaction
                    Dim qtyissued = CDbl(row.Cells("qty_available").Value.ToString())
                    ulottrans.Add("LotNo", row.Cells("LOTNO").Value.ToString())
                    ulottrans.Add("ItemKey", row.Cells("ITEMKEY").Value.ToString())
                    ulottrans.Add("LocationKey", "MAIN")
                    ulottrans.Add("DateExpiry", row.Cells("expiry").Value.ToString())
                    ulottrans.Add("TransactionType", "9")
                    ulottrans.Add("ReceiptDocNo", lottransaction("ReceiptDocNo"))
                    ulottrans.Add("ReceiptDocLineNo", lottransaction("ReceiptDocLineNo"))
                    ulottrans.Add("QtyReceived", "0")
                    ulottrans.Add("VendorlotNo", lottransaction("VendorlotNo"))
                    ulottrans.Add("IssueDocNo", myseqnum)
                    ulottrans.Add("IssueDocLineNo", "1")
                    ulottrans.Add("IssueDate", mydate)
                    ulottrans.Add("QtyIssued", qtyissued)
                    ulottrans.Add("RecUserid", myuserinfo.Item("uname").ToString)
                    ulottrans.Add("RecDate", mydate)
                    ulottrans.Add("Processed", "N")
                    ulottrans.Add("TempQty", "0")
                    ulottrans.Add("QtyForLotAssignment", "0")
                    ulottrans.Add("BinNo", myrec("BinNo"))
                    ulottrans.Add("QtyUsed", "0")
                    msdb.Create("LotTransaction", ulottrans)

                End If

                Dim ulotmaster As New Dictionary(Of String, String)
                ulotmaster.Add("inventory_adjustment", "Y")
                msdb.Update("Lotmaster", ulotmaster, String.Format("LocationKEY = 'MAIN' and ItemKey='{0}' and lotno='{1}' and BinNo = 'A-PREWEIGH'", row.Cells("ITEMKEY").Value.ToString(), row.Cells("LOTNO").Value.ToString()))
                dtgInvertory.Rows.RemoveAt(row.Index)
            End If
        Next

    End Sub

    Private Sub btninvview_Click(sender As Object, e As EventArgs) Handles btninvview.Click


        dgvinv.Rows().Clear()
        Dim q1 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                    WHERE
                     lm.transactiontype = '8'
                    AND lm.BinNo = 'A-PREWEIGH'
                    AND lm.locationkey = 'MAIN'
                    and inventory_adjustment= 'Y'
                    ")
        Dim q1d = bmedb.SelectData(q1)

        For Each item As JObject In q1d
            ' If CDbl(item("QtyAvailable")) > 0.05 Then
            dgvinv.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
            ' End If
        Next
    End Sub

    Private Sub btninvsrch_Click(sender As Object, e As EventArgs) Handles btninvsrch.Click
        Dim q1 As String
        Dim q1d As JArray
        dgvinv.Rows().Clear()
        If txtFilter.SelectedIndex = 0 Then
            q1 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                    WHERE
                     lm.transactiontype = '8'
                    AND lm.BinNo = 'A-PREWEIGH'
                    AND lm.locationkey = 'MAIN'
                    and lm.itemkey = '{0}'
                    and inventory_adjustment ='A'
                    ", txtSearch.Text)
            q1d = bmedb.SelectData(q1)

            For Each item As JObject In q1d
                ' If CDbl(item("QtyAvailable")) > 0.05 Then
                dgvinv.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
                ' End If
            Next
        ElseIf txtFilter.SelectedIndex = 1 Then
            q1 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                    WHERE
                     lm.transactiontype = '8'
                    AND lm.BinNo = 'A-PREWEIGH'
                    AND lm.locationkey = 'MAIN'
                    and lm.lotno = '{0}'
                    and inventory_adjustment= 'Y'
                    ", txtSearch.Text)
            q1d = bmedb.SelectData(q1)

            For Each item As JObject In q1d
                ' If CDbl(item("QtyAvailable")) > 0.05 Then
                dgvinv.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
                ' End If
            Next
        Else
            Dim bcode = mybarcode(txtSearch.Text)
            Dim ikey As String
            Dim lcode As String

            If IsNothing(bcode.Item("02")) = False And IsNothing(bcode.Item("10")) = False Then
                ikey = bcode.Item("02")
                lcode = bcode.Item("10")
                q1 = String.Format(" Select
                        lm.itemkey,
                        lm.lotno,
	                    lm.qtyonhand,
	                    lm.qtycommitsales,
	                    format(lm.Dateexpiry,'d','en-US') as dateexpiry,
	                    lm.BinNo,
	                    (lm.qtyonhand - lm.Qtycommitsales) AS QtyAvailable
                    FROM
	                     lotmaster lm 
                    WHERE
                     lm.transactiontype = '8'
                    AND lm.BinNo = 'A-PREWEIGH'
                    AND lm.locationkey = 'MAIN'
                    and lm.itemkey = '{0}'
                    and lm.lotno = '{1}'
                    and inventory_adjustment= 'Y'
                    ", ikey, lcode)
                q1d = bmedb.SelectData(q1)

                For Each item As JObject In q1d
                    ' If CDbl(item("QtyAvailable")) > 0.05 Then
                    dgvinv.Rows.Add("0", item("itemkey"), item("lotno"), item("qtyonhand"), item("qtycommitsales"), item("QtyAvailable"), item("dateexpiry"))
                    'End If
                Next


            End If


        End If
    End Sub
End Class