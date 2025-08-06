Imports Newtonsoft.Json.Linq

Public Class frmFetchWtItemList
    Private Sub frmFetchWtItemList_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim myfilter = ""
        Dim bmedb As New MsSQL()
        Dim nwfdb As New MySQL()

        DataGridView1.Rows().Clear()

        If frmFetchWt.txtRunNo.Text <> "" Then
            myfilter = " PM.User2 = '" & frmFetchWt.txtRunNo.Text & "' "
        End If

        If myfilter <> "" Then
            myfilter &= " And "
        End If

        If frmFetchWt.txtBatchNo.Text <> "" Then
            myfilter &= " PT.BatchNo = '" & frmFetchWt.txtBatchNo.Text & "'"
        End If



        If myfilter <> "" Then
            myfilter &= " And "

            Dim topicksql_rawmat = String.Format("
                        SELECT DISTINCT
                            (PT.ItemKey),
                            PM.FormulaId,
                            PT.LineTyp,
                            IM.Desc1,
                            PT.Location,
                            PT.StdQtyDispUom,
                            LF.featurevalue,
                            (
                                            CAST (
                                                        PT.stdqtydispuom AS NUMERIC (9, 4)
                                            ) % CAST (
                                                        LF.featurevalue AS NUMERIC (9, 4)
                                            )
                            ) AS 'PartialItem',
                            CAST (
                                            (
                                                        CAST (
                                                                        PT.stdqtydispuom AS NUMERIC (9, 4)
                                                        ) / CAST (
                                                                        LF.featurevalue AS NUMERIC (9, 4)
                                                        )
                                            ) AS INT
                            ) AS 'Bulk',
                            LF2.FeatureValue as packunit,
                IM.wtfrom,
                IM.wtto

                FROM
                            PNMAST PM
                INNER JOIN PNITEM PT ON PM.BatchNo = PT.BatchNo
                INNER JOIN INMAST IM ON IM.Itemkey = PT.itemkey
                LEFT JOIN LotFeaturesValue LF ON LF.itemkey = PT.itemkey
                AND LF.locationkey = PT.location
                AND LF.featureid = 'BAGSIZE'
                LEFT JOIN LotFeaturesValue LF2 ON LF2.itemkey = PT.itemkey AND LF2.locationkey = PT.location AND LF2.featureid = 'PACKUNIT'
                WHERE
                " & myfilter & "
                 LineTyp = 'FI'
                ")

            Dim topickrawmat = bmedb.SelectData(topicksql_rawmat)
            If topickrawmat IsNot Nothing Then
                For Each item As JObject In topickrawmat
                    Dim mPartial As Double
                    If item("PartialItem") = vbNullString Then
                        mPartial = 0
                    Else

                        mPartial = Math.Round(CDbl(item("PartialItem")), 6)
                    End If




                    If mPartial <> 0 Then
                        Dim pickedsql = String.Format("
                           Select runno,itemkey,sum(qty) as qty, GROUP_CONCAT(lotno) as lotno from tbl_rm_allocate_partial where runno = '{0}' and itemkey = '{1}' and batchno = '{2}' group by itemkey,batchno
                    ", frmFetchWt.txtRunNo.Text, item("ItemKey"), frmFetchWt.txtBatchNo.Text)
                        Dim pickeddata As Dictionary(Of String, Object) = nwfdb.SelectDataScalar(pickedsql)

                        Dim stats = ""
                        If pickeddata.Count > 0 Then



                            Dim myquery = String.Format("
                                        SELECT
	                                        itemkey,
	                                        lotno,
	                                        qtyonhand,
	                                        qtycommitsales,
	                                        Dateexpiry,
	                                        BinNo,
	                                        (qtyonhand - Qtycommitsales) AS QtyAvailable
                                        FROM
	                                        LotMaster
                                        WHERE
	                                        Itemkey = '{0}'
                                        AND locationkey = 'MAIN'
                                        AND (qtyonhand - Qtycommitsales) > 0
                                        AND  BinNo = 'A-PREWEIGH'
                                        ORDER BY
	                                        dateexpiry DESC
                                        ", item("ItemKey"))

                            Dim myrec = bmedb.SelectData(myquery)

                            For Each myrec_item As JObject In myrec
                                If pickeddata("qty") >= mPartial Then
                                    stats = "Completed"
                                End If

                                DataGridView1.Rows.Add(item("ItemKey"), item("Desc1"), myrec_item("lotno"), myrec_item("Dateexpiry"), myrec_item("qtyonhand"), myrec_item("qtycommitsales"), myrec_item("QtyAvailable"), mPartial, item("wtfrom"), item("wtto"), stats)
                            Next


                        Else

                            Dim myquery = String.Format("
                                        SELECT
	                                        itemkey,
	                                        lotno,
	                                        qtyonhand,
	                                        qtycommitsales,
	                                        Dateexpiry,
	                                        BinNo,
	                                        (qtyonhand - Qtycommitsales) AS QtyAvailable
                                        FROM
	                                        LotMaster
                                        WHERE
	                                        Itemkey = '{0}'
                                        AND locationkey = 'MAIN'
                                        AND (qtyonhand - Qtycommitsales) > 0
                                        AND  BinNo = 'A-PREWEIGH'
                                        ORDER BY
	                                        dateexpiry DESC
                                        ", item("ItemKey"))

                            Dim myrec = bmedb.SelectData(myquery)

                            For Each myrec_item As JObject In myrec
                                DataGridView1.Rows.Add(item("ItemKey"), item("Desc1"), myrec_item("lotno"), myrec_item("Dateexpiry"), myrec_item("qtyonhand"), myrec_item("qtycommitsales"), myrec_item("QtyAvailable"), mPartial, item("wtfrom"), item("wtto"), stats)
                            Next
                        End If





                    End If

                Next

            End If

        End If


    End Sub

    Private Sub DataGridView1_CellDoubleClick(sender As Object, e As DataGridViewCellEventArgs) Handles DataGridView1.CellDoubleClick
        Dim bmedb As New MsSQL()
        Dim nwfdb As New MySQL()
        frmFetchWt.DataGridView1.Rows.Clear()

        If e.RowIndex >= 0 Then
            Me.Close()
            Dim row As DataGridViewRow = DataGridView1.Rows(e.RowIndex)
            Dim myrequired As String = row.Cells("myrequired").Value
            Dim Itemkey As String = row.Cells("Itemkey").Value
            Dim LotNo As String = row.Cells("LotNo").Value
            Dim Dateexpiry As String = row.Cells("Dateexpiry").Value

            Dim wtfrom1p = (CDbl(row.Cells("myrequired").Value) - (CDbl(row.Cells("myrequired").Value) * 0.01))
            Dim wtfrom As String = If(row.Cells("wtfrom").Value = Nothing, wtfrom1p, row.Cells("wtfrom").Value)

            Dim wtto1p = (CDbl(row.Cells("myrequired").Value) + (CDbl(row.Cells("myrequired").Value) * 0.01))
            Dim wtto As String = If(row.Cells("wtto").Value = Nothing, wtto1p, row.Cells("wtto").Value)

            frmFetchWt.txtItemBarcode.Text = "(02)" & Itemkey & "(10)" & LotNo
            frmFetchWt.txtreqwt.Text = myrequired
            frmFetchWt.txtwtfr.Text = wtfrom
            frmFetchWt.txtwtto.Text = wtto
            frmFetchWt.myitemkey = Itemkey
            frmFetchWt.strlotno = LotNo
            frmFetchWt.Dateexpiry = Dateexpiry

            'PickedList
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
            ", frmFetchWt.txtRunNo.Text, frmFetchWt.txtBatchNo.Text, frmFetchWt.myitemkey)

            Dim mypickdata = nwfdb.SelectData(mypickedsql)
            For Each item As JObject In mypickdata
                frmFetchWt.DataGridView1.Rows.Add("", item("itemkey"), item("lotno"), "", item("qty"), item("batchno"), frmFetchWt.txtRunNo.Text, "O", item("tbl_rm_allocate_partial_id"))
                '  DataGridView1.Rows.Add("", myitemkey, strlotno, Dateexpiry, txtactualwt.Text, txtBatchNo.Text, txtRunNo.Text)
            Next
            frmFetchWt.changetotal()
        End If

    End Sub

End Class