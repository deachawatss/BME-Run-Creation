<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frmPreweigh
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.txtWtrangeto = New System.Windows.Forms.TextBox()
        Me.Label15 = New System.Windows.Forms.Label()
        Me.txtproddate = New System.Windows.Forms.TextBox()
        Me.txtfgsize = New System.Windows.Forms.TextBox()
        Me.Label14 = New System.Windows.Forms.Label()
        Me.txtBatches = New System.Windows.Forms.TextBox()
        Me.Label13 = New System.Windows.Forms.Label()
        Me.txtFGDesc = New System.Windows.Forms.TextBox()
        Me.txtFGitemkey = New System.Windows.Forms.TextBox()
        Me.Label12 = New System.Windows.Forms.Label()
        Me.txtRemainingQty = New System.Windows.Forms.TextBox()
        Me.Label11 = New System.Windows.Forms.Label()
        Me.txtTotalNeeded = New System.Windows.Forms.TextBox()
        Me.Label10 = New System.Windows.Forms.Label()
        Me.txtWtrangefrom = New System.Windows.Forms.TextBox()
        Me.Label9 = New System.Windows.Forms.Label()
        Me.txtWt = New System.Windows.Forms.TextBox()
        Me.Label8 = New System.Windows.Forms.Label()
        Me.txtBagWt = New System.Windows.Forms.TextBox()
        Me.Label7 = New System.Windows.Forms.Label()
        Me.txtbin = New System.Windows.Forms.TextBox()
        Me.Label6 = New System.Windows.Forms.Label()
        Me.txtlotno = New System.Windows.Forms.TextBox()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.txtDesc = New System.Windows.Forms.TextBox()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.txtItemKey = New System.Windows.Forms.TextBox()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.txtBatchno = New System.Windows.Forms.TextBox()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.txtRunNo = New System.Windows.Forms.TextBox()
        Me.dgvtopick = New System.Windows.Forms.DataGridView()
        Me.item = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.batchno = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.partialdata = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.wt = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.balanced = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.LotNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.tp_slotno = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BinNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BagWt = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.wtrangefrom = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.wtrangeto = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.desc = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.allergen = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.nBulk = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.tabPendingPick = New System.Windows.Forms.TabPage()
        Me.txtsuggestedlot = New System.Windows.Forms.TextBox()
        Me.Label18 = New System.Windows.Forms.Label()
        Me.txtrmtopartial = New System.Windows.Forms.TextBox()
        Me.Label17 = New System.Windows.Forms.Label()
        Me.ComboBox1 = New System.Windows.Forms.ComboBox()
        Me.Label16 = New System.Windows.Forms.Label()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.TabControl1 = New System.Windows.Forms.TabControl()
        Me.tabPicked = New System.Windows.Forms.TabPage()
        Me.dgvpicked = New System.Windows.Forms.DataGridView()
        Me.pitem = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pbatchno = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.ppartialdata = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pwt = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pbalanced = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pLotNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.slotno = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pBinNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pBagWt = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pwtrangefrom = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pwtrangeto = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pdesc = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.tabPickedList = New System.Windows.Forms.TabPage()
        Me.dgpickedlist = New System.Windows.Forms.DataGridView()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.btnprintbatch = New System.Windows.Forms.Button()
        Me.btnprintrun = New System.Windows.Forms.Button()
        Me.btnlotno = New System.Windows.Forms.Button()
        Me.btnitemkey = New System.Windows.Forms.Button()
        Me.cmbAllergen = New System.Windows.Forms.ComboBox()
        Me.Label19 = New System.Windows.Forms.Label()
        Me.Button8 = New System.Windows.Forms.Button()
        Me.btnSearchBatchNo = New System.Windows.Forms.Button()
        Me.btnSearchRunNo = New System.Windows.Forms.Button()
        Me.ToolTip1 = New System.Windows.Forms.ToolTip(Me.components)
        Me.DataGridViewTextBoxColumn6 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.DataGridViewTextBoxColumn7 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.DataGridViewTextBoxColumn8 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.DataGridViewTextBoxColumn9 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.partial_id = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.pick_allergen = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.picked_expiry = New System.Windows.Forms.DataGridViewTextBoxColumn()
        CType(Me.dgvtopick, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.tabPendingPick.SuspendLayout()
        Me.GroupBox2.SuspendLayout()
        Me.TabControl1.SuspendLayout()
        Me.tabPicked.SuspendLayout()
        CType(Me.dgvpicked, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.tabPickedList.SuspendLayout()
        CType(Me.dgpickedlist, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox1.SuspendLayout()
        Me.SuspendLayout()
        '
        'txtWtrangeto
        '
        Me.txtWtrangeto.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtWtrangeto.Location = New System.Drawing.Point(596, 789)
        Me.txtWtrangeto.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtWtrangeto.Name = "txtWtrangeto"
        Me.txtWtrangeto.ReadOnly = True
        Me.txtWtrangeto.Size = New System.Drawing.Size(244, 53)
        Me.txtWtrangeto.TabIndex = 43
        Me.txtWtrangeto.Text = "0.000000"
        Me.txtWtrangeto.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label15
        '
        Me.Label15.AutoSize = True
        Me.Label15.BackColor = System.Drawing.Color.Transparent
        Me.Label15.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label15.Location = New System.Drawing.Point(881, 100)
        Me.Label15.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label15.Name = "Label15"
        Me.Label15.Size = New System.Drawing.Size(213, 46)
        Me.Label15.TabIndex = 39
        Me.Label15.Text = "Production"
        '
        'txtproddate
        '
        Me.txtproddate.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtproddate.Location = New System.Drawing.Point(1119, 95)
        Me.txtproddate.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtproddate.Name = "txtproddate"
        Me.txtproddate.ReadOnly = True
        Me.txtproddate.Size = New System.Drawing.Size(292, 53)
        Me.txtproddate.TabIndex = 38
        '
        'txtfgsize
        '
        Me.txtfgsize.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtfgsize.Location = New System.Drawing.Point(1782, 94)
        Me.txtfgsize.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtfgsize.Name = "txtfgsize"
        Me.txtfgsize.ReadOnly = True
        Me.txtfgsize.Size = New System.Drawing.Size(180, 53)
        Me.txtfgsize.TabIndex = 37
        Me.txtfgsize.Text = "0.0000"
        Me.txtfgsize.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label14
        '
        Me.Label14.AutoSize = True
        Me.Label14.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label14.Location = New System.Drawing.Point(1726, 98)
        Me.Label14.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label14.Name = "Label14"
        Me.Label14.Size = New System.Drawing.Size(60, 46)
        Me.Label14.TabIndex = 36
        Me.Label14.Text = "@"
        '
        'txtBatches
        '
        Me.txtBatches.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtBatches.Location = New System.Drawing.Point(1576, 94)
        Me.txtBatches.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtBatches.Name = "txtBatches"
        Me.txtBatches.ReadOnly = True
        Me.txtBatches.Size = New System.Drawing.Size(152, 53)
        Me.txtBatches.TabIndex = 35
        Me.txtBatches.Text = "0"
        Me.txtBatches.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label13
        '
        Me.Label13.AutoSize = True
        Me.Label13.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label13.Location = New System.Drawing.Point(1416, 99)
        Me.Label13.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label13.Name = "Label13"
        Me.Label13.Size = New System.Drawing.Size(165, 46)
        Me.Label13.TabIndex = 34
        Me.Label13.Text = "Batches"
        '
        'txtFGDesc
        '
        Me.txtFGDesc.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.txtFGDesc.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtFGDesc.Location = New System.Drawing.Point(1419, 31)
        Me.txtFGDesc.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtFGDesc.Name = "txtFGDesc"
        Me.txtFGDesc.ReadOnly = True
        Me.txtFGDesc.Size = New System.Drawing.Size(515, 53)
        Me.txtFGDesc.TabIndex = 33
        '
        'txtFGitemkey
        '
        Me.txtFGitemkey.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtFGitemkey.Location = New System.Drawing.Point(1119, 31)
        Me.txtFGitemkey.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtFGitemkey.Name = "txtFGitemkey"
        Me.txtFGitemkey.ReadOnly = True
        Me.txtFGitemkey.Size = New System.Drawing.Size(292, 53)
        Me.txtFGitemkey.TabIndex = 32
        '
        'Label12
        '
        Me.Label12.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label12.AutoSize = True
        Me.Label12.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label12.Location = New System.Drawing.Point(880, 39)
        Me.Label12.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label12.Name = "Label12"
        Me.Label12.Size = New System.Drawing.Size(241, 46)
        Me.Label12.TabIndex = 31
        Me.Label12.Text = "FG Item Key"
        '
        'txtRemainingQty
        '
        Me.txtRemainingQty.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtRemainingQty.Location = New System.Drawing.Point(319, 968)
        Me.txtRemainingQty.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtRemainingQty.Name = "txtRemainingQty"
        Me.txtRemainingQty.ReadOnly = True
        Me.txtRemainingQty.Size = New System.Drawing.Size(255, 53)
        Me.txtRemainingQty.TabIndex = 21
        Me.txtRemainingQty.Text = "0.0000"
        Me.txtRemainingQty.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label11
        '
        Me.Label11.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label11.AutoSize = True
        Me.Label11.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label11.Location = New System.Drawing.Point(11, 972)
        Me.Label11.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label11.Name = "Label11"
        Me.Label11.Size = New System.Drawing.Size(283, 46)
        Me.Label11.TabIndex = 20
        Me.Label11.Text = "Remaining Qty"
        '
        'txtTotalNeeded
        '
        Me.txtTotalNeeded.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtTotalNeeded.Location = New System.Drawing.Point(319, 878)
        Me.txtTotalNeeded.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtTotalNeeded.Name = "txtTotalNeeded"
        Me.txtTotalNeeded.ReadOnly = True
        Me.txtTotalNeeded.Size = New System.Drawing.Size(255, 53)
        Me.txtTotalNeeded.TabIndex = 19
        Me.txtTotalNeeded.Text = "0.0000"
        Me.txtTotalNeeded.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label10
        '
        Me.Label10.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label10.AutoSize = True
        Me.Label10.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label10.Location = New System.Drawing.Point(11, 882)
        Me.Label10.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label10.Name = "Label10"
        Me.Label10.Size = New System.Drawing.Size(259, 46)
        Me.Label10.TabIndex = 18
        Me.Label10.Text = "Total Needed"
        '
        'txtWtrangefrom
        '
        Me.txtWtrangefrom.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtWtrangefrom.Location = New System.Drawing.Point(319, 789)
        Me.txtWtrangefrom.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtWtrangefrom.Name = "txtWtrangefrom"
        Me.txtWtrangefrom.ReadOnly = True
        Me.txtWtrangefrom.Size = New System.Drawing.Size(255, 53)
        Me.txtWtrangefrom.TabIndex = 17
        Me.txtWtrangefrom.Text = "0.000000"
        Me.txtWtrangefrom.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label9
        '
        Me.Label9.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label9.AutoSize = True
        Me.Label9.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label9.Location = New System.Drawing.Point(11, 792)
        Me.Label9.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label9.Name = "Label9"
        Me.Label9.Size = New System.Drawing.Size(272, 46)
        Me.Label9.TabIndex = 16
        Me.Label9.Text = "Weight Range"
        '
        'txtWt
        '
        Me.txtWt.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtWt.Location = New System.Drawing.Point(319, 706)
        Me.txtWt.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtWt.Name = "txtWt"
        Me.txtWt.ReadOnly = True
        Me.txtWt.Size = New System.Drawing.Size(255, 53)
        Me.txtWt.TabIndex = 15
        Me.txtWt.Text = "0.0000"
        Me.txtWt.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label8
        '
        Me.Label8.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label8.AutoSize = True
        Me.Label8.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label8.Location = New System.Drawing.Point(11, 709)
        Me.Label8.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label8.Name = "Label8"
        Me.Label8.Size = New System.Drawing.Size(144, 46)
        Me.Label8.TabIndex = 14
        Me.Label8.Text = "Weight"
        '
        'txtBagWt
        '
        Me.txtBagWt.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtBagWt.Location = New System.Drawing.Point(319, 620)
        Me.txtBagWt.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtBagWt.Name = "txtBagWt"
        Me.txtBagWt.ReadOnly = True
        Me.txtBagWt.Size = New System.Drawing.Size(255, 53)
        Me.txtBagWt.TabIndex = 13
        Me.txtBagWt.Text = "0.0000"
        Me.txtBagWt.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label7
        '
        Me.Label7.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label7.AutoSize = True
        Me.Label7.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label7.Location = New System.Drawing.Point(11, 624)
        Me.Label7.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label7.Name = "Label7"
        Me.Label7.Size = New System.Drawing.Size(226, 46)
        Me.Label7.TabIndex = 12
        Me.Label7.Text = "Bag Weight"
        '
        'txtbin
        '
        Me.txtbin.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtbin.Location = New System.Drawing.Point(319, 455)
        Me.txtbin.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtbin.Name = "txtbin"
        Me.txtbin.ReadOnly = True
        Me.txtbin.Size = New System.Drawing.Size(522, 53)
        Me.txtbin.TabIndex = 11
        '
        'Label6
        '
        Me.Label6.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label6.AutoSize = True
        Me.Label6.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label6.Location = New System.Drawing.Point(12, 460)
        Me.Label6.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(141, 46)
        Me.Label6.TabIndex = 10
        Me.Label6.Text = "Bin No"
        '
        'txtlotno
        '
        Me.txtlotno.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtlotno.Location = New System.Drawing.Point(319, 291)
        Me.txtlotno.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtlotno.Name = "txtlotno"
        Me.txtlotno.Size = New System.Drawing.Size(445, 53)
        Me.txtlotno.TabIndex = 9
        '
        'Label5
        '
        Me.Label5.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label5.AutoSize = True
        Me.Label5.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label5.Location = New System.Drawing.Point(11, 296)
        Me.Label5.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(139, 46)
        Me.Label5.TabIndex = 8
        Me.Label5.Text = "Lot No"
        '
        'txtDesc
        '
        Me.txtDesc.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtDesc.Location = New System.Drawing.Point(319, 370)
        Me.txtDesc.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtDesc.Name = "txtDesc"
        Me.txtDesc.ReadOnly = True
        Me.txtDesc.Size = New System.Drawing.Size(522, 53)
        Me.txtDesc.TabIndex = 7
        '
        'Label4
        '
        Me.Label4.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label4.Location = New System.Drawing.Point(10, 375)
        Me.Label4.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(221, 46)
        Me.Label4.TabIndex = 6
        Me.Label4.Text = "Description"
        '
        'txtItemKey
        '
        Me.txtItemKey.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtItemKey.Location = New System.Drawing.Point(319, 204)
        Me.txtItemKey.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtItemKey.Name = "txtItemKey"
        Me.txtItemKey.Size = New System.Drawing.Size(445, 53)
        Me.txtItemKey.TabIndex = 5
        '
        'Label3
        '
        Me.Label3.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label3.Location = New System.Drawing.Point(11, 210)
        Me.Label3.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(175, 46)
        Me.Label3.TabIndex = 4
        Me.Label3.Text = "Item Key"
        '
        'txtBatchno
        '
        Me.txtBatchno.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtBatchno.Location = New System.Drawing.Point(319, 119)
        Me.txtBatchno.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtBatchno.Name = "txtBatchno"
        Me.txtBatchno.Size = New System.Drawing.Size(381, 53)
        Me.txtBatchno.TabIndex = 3
        '
        'Label2
        '
        Me.Label2.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label2.Location = New System.Drawing.Point(11, 124)
        Me.Label2.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(186, 46)
        Me.Label2.TabIndex = 2
        Me.Label2.Text = "Batch No"
        '
        'Label1
        '
        Me.Label1.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label1.Location = New System.Drawing.Point(11, 39)
        Me.Label1.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(156, 46)
        Me.Label1.TabIndex = 0
        Me.Label1.Text = "Run No"
        '
        'txtRunNo
        '
        Me.txtRunNo.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtRunNo.Location = New System.Drawing.Point(319, 34)
        Me.txtRunNo.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtRunNo.Name = "txtRunNo"
        Me.txtRunNo.Size = New System.Drawing.Size(381, 53)
        Me.txtRunNo.TabIndex = 1
        '
        'dgvtopick
        '
        Me.dgvtopick.AllowUserToAddRows = False
        Me.dgvtopick.AllowUserToDeleteRows = False
        Me.dgvtopick.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dgvtopick.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.item, Me.batchno, Me.partialdata, Me.wt, Me.balanced, Me.LotNo, Me.tp_slotno, Me.BinNo, Me.BagWt, Me.wtrangefrom, Me.wtrangeto, Me.desc, Me.allergen, Me.nBulk})
        Me.dgvtopick.Dock = System.Windows.Forms.DockStyle.Fill
        Me.dgvtopick.Location = New System.Drawing.Point(4, 2)
        Me.dgvtopick.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.dgvtopick.Name = "dgvtopick"
        Me.dgvtopick.ReadOnly = True
        Me.dgvtopick.RowHeadersWidth = 51
        Me.dgvtopick.RowTemplate.Height = 30
        Me.dgvtopick.Size = New System.Drawing.Size(1007, 782)
        Me.dgvtopick.TabIndex = 2
        '
        'item
        '
        Me.item.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.AllCells
        Me.item.HeaderText = "Item"
        Me.item.MinimumWidth = 6
        Me.item.Name = "item"
        Me.item.ReadOnly = True
        Me.item.Width = 103
        '
        'batchno
        '
        Me.batchno.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.batchno.HeaderText = "Batch No"
        Me.batchno.MinimumWidth = 6
        Me.batchno.Name = "batchno"
        Me.batchno.ReadOnly = True
        '
        'partialdata
        '
        Me.partialdata.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.partialdata.HeaderText = "Partial"
        Me.partialdata.MinimumWidth = 6
        Me.partialdata.Name = "partialdata"
        Me.partialdata.ReadOnly = True
        '
        'wt
        '
        Me.wt.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.wt.HeaderText = "Weighted"
        Me.wt.MinimumWidth = 6
        Me.wt.Name = "wt"
        Me.wt.ReadOnly = True
        '
        'balanced
        '
        Me.balanced.HeaderText = "Balance"
        Me.balanced.MinimumWidth = 6
        Me.balanced.Name = "balanced"
        Me.balanced.ReadOnly = True
        Me.balanced.Width = 125
        '
        'LotNo
        '
        Me.LotNo.HeaderText = "Lot No"
        Me.LotNo.MinimumWidth = 6
        Me.LotNo.Name = "LotNo"
        Me.LotNo.ReadOnly = True
        Me.LotNo.Visible = False
        Me.LotNo.Width = 125
        '
        'tp_slotno
        '
        Me.tp_slotno.HeaderText = "Suggested Lot"
        Me.tp_slotno.MinimumWidth = 6
        Me.tp_slotno.Name = "tp_slotno"
        Me.tp_slotno.ReadOnly = True
        Me.tp_slotno.Visible = False
        Me.tp_slotno.Width = 125
        '
        'BinNo
        '
        Me.BinNo.HeaderText = "Bin No"
        Me.BinNo.MinimumWidth = 6
        Me.BinNo.Name = "BinNo"
        Me.BinNo.ReadOnly = True
        Me.BinNo.Visible = False
        Me.BinNo.Width = 125
        '
        'BagWt
        '
        Me.BagWt.HeaderText = "Bag Weight"
        Me.BagWt.MinimumWidth = 6
        Me.BagWt.Name = "BagWt"
        Me.BagWt.ReadOnly = True
        Me.BagWt.Visible = False
        Me.BagWt.Width = 125
        '
        'wtrangefrom
        '
        Me.wtrangefrom.HeaderText = "Weight Range From"
        Me.wtrangefrom.MinimumWidth = 6
        Me.wtrangefrom.Name = "wtrangefrom"
        Me.wtrangefrom.ReadOnly = True
        Me.wtrangefrom.Visible = False
        Me.wtrangefrom.Width = 125
        '
        'wtrangeto
        '
        Me.wtrangeto.HeaderText = "Weight Range To"
        Me.wtrangeto.MinimumWidth = 6
        Me.wtrangeto.Name = "wtrangeto"
        Me.wtrangeto.ReadOnly = True
        Me.wtrangeto.Visible = False
        Me.wtrangeto.Width = 125
        '
        'desc
        '
        Me.desc.HeaderText = "Description"
        Me.desc.MinimumWidth = 6
        Me.desc.Name = "desc"
        Me.desc.ReadOnly = True
        Me.desc.Visible = False
        Me.desc.Width = 125
        '
        'allergen
        '
        Me.allergen.HeaderText = "Allergen"
        Me.allergen.MinimumWidth = 6
        Me.allergen.Name = "allergen"
        Me.allergen.ReadOnly = True
        Me.allergen.Width = 125
        '
        'nBulk
        '
        Me.nBulk.HeaderText = "nBulk"
        Me.nBulk.MinimumWidth = 6
        Me.nBulk.Name = "nBulk"
        Me.nBulk.ReadOnly = True
        Me.nBulk.Visible = False
        Me.nBulk.Width = 125
        '
        'tabPendingPick
        '
        Me.tabPendingPick.Controls.Add(Me.dgvtopick)
        Me.tabPendingPick.Location = New System.Drawing.Point(4, 45)
        Me.tabPendingPick.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.tabPendingPick.Name = "tabPendingPick"
        Me.tabPendingPick.Padding = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.tabPendingPick.Size = New System.Drawing.Size(1015, 786)
        Me.tabPendingPick.TabIndex = 0
        Me.tabPendingPick.Text = "Pending to Picked"
        Me.tabPendingPick.UseVisualStyleBackColor = True
        '
        'txtsuggestedlot
        '
        Me.txtsuggestedlot.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtsuggestedlot.Location = New System.Drawing.Point(319, 539)
        Me.txtsuggestedlot.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtsuggestedlot.Name = "txtsuggestedlot"
        Me.txtsuggestedlot.ReadOnly = True
        Me.txtsuggestedlot.Size = New System.Drawing.Size(522, 53)
        Me.txtsuggestedlot.TabIndex = 50
        '
        'Label18
        '
        Me.Label18.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label18.AutoSize = True
        Me.Label18.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label18.Location = New System.Drawing.Point(11, 544)
        Me.Label18.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label18.Name = "Label18"
        Me.Label18.Size = New System.Drawing.Size(277, 46)
        Me.Label18.TabIndex = 49
        Me.Label18.Text = "Suggested Lot"
        '
        'txtrmtopartial
        '
        Me.txtrmtopartial.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtrmtopartial.Location = New System.Drawing.Point(1659, 161)
        Me.txtrmtopartial.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.txtrmtopartial.Name = "txtrmtopartial"
        Me.txtrmtopartial.ReadOnly = True
        Me.txtrmtopartial.Size = New System.Drawing.Size(270, 53)
        Me.txtrmtopartial.TabIndex = 48
        Me.txtrmtopartial.Visible = False
        '
        'Label17
        '
        Me.Label17.AutoSize = True
        Me.Label17.BackColor = System.Drawing.Color.Transparent
        Me.Label17.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label17.Location = New System.Drawing.Point(1408, 170)
        Me.Label17.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label17.Name = "Label17"
        Me.Label17.Size = New System.Drawing.Size(251, 46)
        Me.Label17.TabIndex = 47
        Me.Label17.Text = "RM to Partial"
        Me.Label17.Visible = False
        '
        'ComboBox1
        '
        Me.ComboBox1.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.ComboBox1.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.ComboBox1.FormattingEnabled = True
        Me.ComboBox1.Items.AddRange(New Object() {"SCALE 1", "SCALE 2"})
        Me.ComboBox1.Location = New System.Drawing.Point(319, 1058)
        Me.ComboBox1.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.ComboBox1.Name = "ComboBox1"
        Me.ComboBox1.Size = New System.Drawing.Size(534, 54)
        Me.ComboBox1.TabIndex = 46
        '
        'Label16
        '
        Me.Label16.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Label16.AutoSize = True
        Me.Label16.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label16.Location = New System.Drawing.Point(11, 1061)
        Me.Label16.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label16.Name = "Label16"
        Me.Label16.Size = New System.Drawing.Size(152, 46)
        Me.Label16.TabIndex = 45
        Me.Label16.Text = "SCALE"
        Me.Label16.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'GroupBox2
        '
        Me.GroupBox2.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.GroupBox2.BackColor = System.Drawing.SystemColors.Info
        Me.GroupBox2.Controls.Add(Me.TabControl1)
        Me.GroupBox2.Font = New System.Drawing.Font("Microsoft Sans Serif", 15.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.GroupBox2.Location = New System.Drawing.Point(890, 225)
        Me.GroupBox2.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Padding = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.GroupBox2.Size = New System.Drawing.Size(1037, 872)
        Me.GroupBox2.TabIndex = 44
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "Batch Ticket Partials"
        '
        'TabControl1
        '
        Me.TabControl1.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.TabControl1.Controls.Add(Me.tabPendingPick)
        Me.TabControl1.Controls.Add(Me.tabPicked)
        Me.TabControl1.Controls.Add(Me.tabPickedList)
        Me.TabControl1.Font = New System.Drawing.Font("Microsoft Sans Serif", 18.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.TabControl1.Location = New System.Drawing.Point(6, 38)
        Me.TabControl1.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.TabControl1.Name = "TabControl1"
        Me.TabControl1.SelectedIndex = 0
        Me.TabControl1.Size = New System.Drawing.Size(1023, 835)
        Me.TabControl1.TabIndex = 0
        '
        'tabPicked
        '
        Me.tabPicked.Controls.Add(Me.dgvpicked)
        Me.tabPicked.Location = New System.Drawing.Point(4, 45)
        Me.tabPicked.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.tabPicked.Name = "tabPicked"
        Me.tabPicked.Padding = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.tabPicked.Size = New System.Drawing.Size(1015, 786)
        Me.tabPicked.TabIndex = 1
        Me.tabPicked.Text = "Picked Summary"
        Me.tabPicked.UseVisualStyleBackColor = True
        '
        'dgvpicked
        '
        Me.dgvpicked.AllowUserToAddRows = False
        Me.dgvpicked.AllowUserToDeleteRows = False
        Me.dgvpicked.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dgvpicked.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.pitem, Me.pbatchno, Me.ppartialdata, Me.pwt, Me.pbalanced, Me.pLotNo, Me.slotno, Me.pBinNo, Me.pBagWt, Me.pwtrangefrom, Me.pwtrangeto, Me.pdesc})
        Me.dgvpicked.Dock = System.Windows.Forms.DockStyle.Fill
        Me.dgvpicked.Location = New System.Drawing.Point(4, 2)
        Me.dgvpicked.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.dgvpicked.Name = "dgvpicked"
        Me.dgvpicked.ReadOnly = True
        Me.dgvpicked.RowHeadersWidth = 51
        Me.dgvpicked.RowTemplate.Height = 30
        Me.dgvpicked.Size = New System.Drawing.Size(1007, 782)
        Me.dgvpicked.TabIndex = 3
        '
        'pitem
        '
        Me.pitem.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.pitem.HeaderText = "Item"
        Me.pitem.MinimumWidth = 6
        Me.pitem.Name = "pitem"
        Me.pitem.ReadOnly = True
        '
        'pbatchno
        '
        Me.pbatchno.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.pbatchno.HeaderText = "Batch No"
        Me.pbatchno.MinimumWidth = 6
        Me.pbatchno.Name = "pbatchno"
        Me.pbatchno.ReadOnly = True
        '
        'ppartialdata
        '
        Me.ppartialdata.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.ppartialdata.HeaderText = "Partial"
        Me.ppartialdata.MinimumWidth = 6
        Me.ppartialdata.Name = "ppartialdata"
        Me.ppartialdata.ReadOnly = True
        '
        'pwt
        '
        Me.pwt.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.pwt.HeaderText = "Weighted"
        Me.pwt.MinimumWidth = 6
        Me.pwt.Name = "pwt"
        Me.pwt.ReadOnly = True
        '
        'pbalanced
        '
        Me.pbalanced.HeaderText = "Balance"
        Me.pbalanced.MinimumWidth = 6
        Me.pbalanced.Name = "pbalanced"
        Me.pbalanced.ReadOnly = True
        Me.pbalanced.Width = 125
        '
        'pLotNo
        '
        Me.pLotNo.HeaderText = "Lot No"
        Me.pLotNo.MinimumWidth = 6
        Me.pLotNo.Name = "pLotNo"
        Me.pLotNo.ReadOnly = True
        Me.pLotNo.Visible = False
        Me.pLotNo.Width = 125
        '
        'slotno
        '
        Me.slotno.HeaderText = "Suggeste Lot"
        Me.slotno.MinimumWidth = 6
        Me.slotno.Name = "slotno"
        Me.slotno.ReadOnly = True
        Me.slotno.Visible = False
        Me.slotno.Width = 125
        '
        'pBinNo
        '
        Me.pBinNo.HeaderText = "Bin No"
        Me.pBinNo.MinimumWidth = 6
        Me.pBinNo.Name = "pBinNo"
        Me.pBinNo.ReadOnly = True
        Me.pBinNo.Visible = False
        Me.pBinNo.Width = 125
        '
        'pBagWt
        '
        Me.pBagWt.HeaderText = "Bag Weight"
        Me.pBagWt.MinimumWidth = 6
        Me.pBagWt.Name = "pBagWt"
        Me.pBagWt.ReadOnly = True
        Me.pBagWt.Visible = False
        Me.pBagWt.Width = 125
        '
        'pwtrangefrom
        '
        Me.pwtrangefrom.HeaderText = "Weight Range From"
        Me.pwtrangefrom.MinimumWidth = 6
        Me.pwtrangefrom.Name = "pwtrangefrom"
        Me.pwtrangefrom.ReadOnly = True
        Me.pwtrangefrom.Visible = False
        Me.pwtrangefrom.Width = 125
        '
        'pwtrangeto
        '
        Me.pwtrangeto.HeaderText = "Weight Range To"
        Me.pwtrangeto.MinimumWidth = 6
        Me.pwtrangeto.Name = "pwtrangeto"
        Me.pwtrangeto.ReadOnly = True
        Me.pwtrangeto.Visible = False
        Me.pwtrangeto.Width = 125
        '
        'pdesc
        '
        Me.pdesc.HeaderText = "Description"
        Me.pdesc.MinimumWidth = 6
        Me.pdesc.Name = "pdesc"
        Me.pdesc.ReadOnly = True
        Me.pdesc.Visible = False
        Me.pdesc.Width = 125
        '
        'tabPickedList
        '
        Me.tabPickedList.Controls.Add(Me.dgpickedlist)
        Me.tabPickedList.Location = New System.Drawing.Point(4, 45)
        Me.tabPickedList.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.tabPickedList.Name = "tabPickedList"
        Me.tabPickedList.Padding = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.tabPickedList.Size = New System.Drawing.Size(1015, 786)
        Me.tabPickedList.TabIndex = 2
        Me.tabPickedList.Text = "Picked List"
        Me.tabPickedList.UseVisualStyleBackColor = True
        '
        'dgpickedlist
        '
        Me.dgpickedlist.AllowUserToAddRows = False
        Me.dgpickedlist.AllowUserToDeleteRows = False
        Me.dgpickedlist.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dgpickedlist.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.DataGridViewTextBoxColumn6, Me.DataGridViewTextBoxColumn7, Me.DataGridViewTextBoxColumn8, Me.DataGridViewTextBoxColumn9, Me.partial_id, Me.pick_allergen, Me.picked_expiry})
        Me.dgpickedlist.Dock = System.Windows.Forms.DockStyle.Fill
        Me.dgpickedlist.Location = New System.Drawing.Point(4, 2)
        Me.dgpickedlist.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.dgpickedlist.Name = "dgpickedlist"
        Me.dgpickedlist.ReadOnly = True
        Me.dgpickedlist.RowHeadersWidth = 51
        Me.dgpickedlist.RowTemplate.Height = 30
        Me.dgpickedlist.Size = New System.Drawing.Size(1007, 782)
        Me.dgpickedlist.TabIndex = 1
        '
        'GroupBox1
        '
        Me.GroupBox1.BackColor = System.Drawing.SystemColors.MenuHighlight
        Me.GroupBox1.Controls.Add(Me.btnprintbatch)
        Me.GroupBox1.Controls.Add(Me.btnprintrun)
        Me.GroupBox1.Controls.Add(Me.btnlotno)
        Me.GroupBox1.Controls.Add(Me.btnitemkey)
        Me.GroupBox1.Controls.Add(Me.cmbAllergen)
        Me.GroupBox1.Controls.Add(Me.Label19)
        Me.GroupBox1.Controls.Add(Me.txtsuggestedlot)
        Me.GroupBox1.Controls.Add(Me.Label18)
        Me.GroupBox1.Controls.Add(Me.txtrmtopartial)
        Me.GroupBox1.Controls.Add(Me.Label17)
        Me.GroupBox1.Controls.Add(Me.ComboBox1)
        Me.GroupBox1.Controls.Add(Me.Label16)
        Me.GroupBox1.Controls.Add(Me.GroupBox2)
        Me.GroupBox1.Controls.Add(Me.txtWtrangeto)
        Me.GroupBox1.Controls.Add(Me.Label15)
        Me.GroupBox1.Controls.Add(Me.txtproddate)
        Me.GroupBox1.Controls.Add(Me.txtfgsize)
        Me.GroupBox1.Controls.Add(Me.Label14)
        Me.GroupBox1.Controls.Add(Me.txtBatches)
        Me.GroupBox1.Controls.Add(Me.Label13)
        Me.GroupBox1.Controls.Add(Me.txtFGDesc)
        Me.GroupBox1.Controls.Add(Me.txtFGitemkey)
        Me.GroupBox1.Controls.Add(Me.Label12)
        Me.GroupBox1.Controls.Add(Me.Button8)
        Me.GroupBox1.Controls.Add(Me.btnSearchBatchNo)
        Me.GroupBox1.Controls.Add(Me.btnSearchRunNo)
        Me.GroupBox1.Controls.Add(Me.txtRemainingQty)
        Me.GroupBox1.Controls.Add(Me.Label11)
        Me.GroupBox1.Controls.Add(Me.txtTotalNeeded)
        Me.GroupBox1.Controls.Add(Me.Label10)
        Me.GroupBox1.Controls.Add(Me.txtWtrangefrom)
        Me.GroupBox1.Controls.Add(Me.Label9)
        Me.GroupBox1.Controls.Add(Me.txtWt)
        Me.GroupBox1.Controls.Add(Me.Label8)
        Me.GroupBox1.Controls.Add(Me.txtBagWt)
        Me.GroupBox1.Controls.Add(Me.Label7)
        Me.GroupBox1.Controls.Add(Me.txtbin)
        Me.GroupBox1.Controls.Add(Me.Label6)
        Me.GroupBox1.Controls.Add(Me.txtlotno)
        Me.GroupBox1.Controls.Add(Me.Label5)
        Me.GroupBox1.Controls.Add(Me.txtDesc)
        Me.GroupBox1.Controls.Add(Me.Label4)
        Me.GroupBox1.Controls.Add(Me.txtItemKey)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.txtBatchno)
        Me.GroupBox1.Controls.Add(Me.Label2)
        Me.GroupBox1.Controls.Add(Me.txtRunNo)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Dock = System.Windows.Forms.DockStyle.Fill
        Me.GroupBox1.Location = New System.Drawing.Point(0, 0)
        Me.GroupBox1.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Padding = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.GroupBox1.Size = New System.Drawing.Size(1942, 1102)
        Me.GroupBox1.TabIndex = 1
        Me.GroupBox1.TabStop = False
        '
        'btnprintbatch
        '
        Me.btnprintbatch.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.btnprintbatch.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_print_100
        Me.btnprintbatch.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.btnprintbatch.Location = New System.Drawing.Point(796, 112)
        Me.btnprintbatch.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.btnprintbatch.Name = "btnprintbatch"
        Me.btnprintbatch.Size = New System.Drawing.Size(74, 66)
        Me.btnprintbatch.TabIndex = 56
        Me.btnprintbatch.UseVisualStyleBackColor = True
        '
        'btnprintrun
        '
        Me.btnprintrun.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.btnprintrun.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_print_100
        Me.btnprintrun.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.btnprintrun.Location = New System.Drawing.Point(796, 31)
        Me.btnprintrun.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.btnprintrun.Name = "btnprintrun"
        Me.btnprintrun.Size = New System.Drawing.Size(74, 66)
        Me.btnprintrun.TabIndex = 55
        Me.btnprintrun.UseVisualStyleBackColor = True
        '
        'btnlotno
        '
        Me.btnlotno.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.btnlotno.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_search_32
        Me.btnlotno.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.btnlotno.Location = New System.Drawing.Point(780, 282)
        Me.btnlotno.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.btnlotno.Name = "btnlotno"
        Me.btnlotno.Size = New System.Drawing.Size(74, 68)
        Me.btnlotno.TabIndex = 54
        Me.btnlotno.UseVisualStyleBackColor = True
        '
        'btnitemkey
        '
        Me.btnitemkey.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.btnitemkey.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_search_32
        Me.btnitemkey.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.btnitemkey.Location = New System.Drawing.Point(780, 194)
        Me.btnitemkey.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.btnitemkey.Name = "btnitemkey"
        Me.btnitemkey.Size = New System.Drawing.Size(74, 68)
        Me.btnitemkey.TabIndex = 53
        Me.btnitemkey.UseVisualStyleBackColor = True
        Me.btnitemkey.Visible = False
        '
        'cmbAllergen
        '
        Me.cmbAllergen.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbAllergen.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.cmbAllergen.FormattingEnabled = True
        Me.cmbAllergen.Items.AddRange(New Object() {"ALL", "NON-ALLERGEN", "ALLERGEN"})
        Me.cmbAllergen.Location = New System.Drawing.Point(1119, 158)
        Me.cmbAllergen.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.cmbAllergen.Name = "cmbAllergen"
        Me.cmbAllergen.Size = New System.Drawing.Size(292, 54)
        Me.cmbAllergen.TabIndex = 52
        '
        'Label19
        '
        Me.Label19.AutoSize = True
        Me.Label19.BackColor = System.Drawing.Color.Transparent
        Me.Label19.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label19.Location = New System.Drawing.Point(888, 161)
        Me.Label19.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.Label19.Name = "Label19"
        Me.Label19.Size = New System.Drawing.Size(166, 46)
        Me.Label19.TabIndex = 51
        Me.Label19.Text = "Allergen"
        '
        'Button8
        '
        Me.Button8.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Button8.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.Button8.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_scale_64
        Me.Button8.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.Button8.Enabled = False
        Me.Button8.Location = New System.Drawing.Point(889, 1141)
        Me.Button8.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.Button8.Name = "Button8"
        Me.Button8.Size = New System.Drawing.Size(653, 421)
        Me.Button8.TabIndex = 29
        Me.Button8.UseVisualStyleBackColor = True
        Me.Button8.Visible = False
        '
        'btnSearchBatchNo
        '
        Me.btnSearchBatchNo.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.btnSearchBatchNo.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_search_32
        Me.btnSearchBatchNo.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.btnSearchBatchNo.Location = New System.Drawing.Point(709, 112)
        Me.btnSearchBatchNo.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.btnSearchBatchNo.Name = "btnSearchBatchNo"
        Me.btnSearchBatchNo.Size = New System.Drawing.Size(74, 68)
        Me.btnSearchBatchNo.TabIndex = 23
        Me.btnSearchBatchNo.UseVisualStyleBackColor = True
        '
        'btnSearchRunNo
        '
        Me.btnSearchRunNo.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.btnSearchRunNo.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_search_32
        Me.btnSearchRunNo.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.btnSearchRunNo.Location = New System.Drawing.Point(711, 31)
        Me.btnSearchRunNo.Margin = New System.Windows.Forms.Padding(4, 2, 4, 2)
        Me.btnSearchRunNo.Name = "btnSearchRunNo"
        Me.btnSearchRunNo.Size = New System.Drawing.Size(74, 66)
        Me.btnSearchRunNo.TabIndex = 22
        Me.btnSearchRunNo.UseVisualStyleBackColor = True
        '
        'DataGridViewTextBoxColumn6
        '
        Me.DataGridViewTextBoxColumn6.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn6.HeaderText = "Item"
        Me.DataGridViewTextBoxColumn6.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn6.Name = "DataGridViewTextBoxColumn6"
        Me.DataGridViewTextBoxColumn6.ReadOnly = True
        '
        'DataGridViewTextBoxColumn7
        '
        Me.DataGridViewTextBoxColumn7.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn7.HeaderText = "Batch No"
        Me.DataGridViewTextBoxColumn7.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn7.Name = "DataGridViewTextBoxColumn7"
        Me.DataGridViewTextBoxColumn7.ReadOnly = True
        '
        'DataGridViewTextBoxColumn8
        '
        Me.DataGridViewTextBoxColumn8.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn8.HeaderText = "Lot No"
        Me.DataGridViewTextBoxColumn8.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn8.Name = "DataGridViewTextBoxColumn8"
        Me.DataGridViewTextBoxColumn8.ReadOnly = True
        '
        'DataGridViewTextBoxColumn9
        '
        Me.DataGridViewTextBoxColumn9.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn9.HeaderText = "Weighted"
        Me.DataGridViewTextBoxColumn9.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn9.Name = "DataGridViewTextBoxColumn9"
        Me.DataGridViewTextBoxColumn9.ReadOnly = True
        '
        'partial_id
        '
        Me.partial_id.HeaderText = "partial_id"
        Me.partial_id.MinimumWidth = 6
        Me.partial_id.Name = "partial_id"
        Me.partial_id.ReadOnly = True
        Me.partial_id.Visible = False
        Me.partial_id.Width = 125
        '
        'pick_allergen
        '
        Me.pick_allergen.HeaderText = "pick_allergen"
        Me.pick_allergen.MinimumWidth = 6
        Me.pick_allergen.Name = "pick_allergen"
        Me.pick_allergen.ReadOnly = True
        Me.pick_allergen.Visible = False
        Me.pick_allergen.Width = 125
        '
        'picked_expiry
        '
        Me.picked_expiry.HeaderText = "picked_expiry"
        Me.picked_expiry.MinimumWidth = 6
        Me.picked_expiry.Name = "picked_expiry"
        Me.picked_expiry.ReadOnly = True
        Me.picked_expiry.Visible = False
        Me.picked_expiry.Width = 125
        '
        'frmPreweigh
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(120.0!, 120.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Dpi
        Me.AutoSize = True
        Me.ClientSize = New System.Drawing.Size(1942, 1102)
        Me.Controls.Add(Me.GroupBox1)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None
        Me.Margin = New System.Windows.Forms.Padding(4)
        Me.Name = "frmPreweigh"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "frmPreweigh"
        CType(Me.dgvtopick, System.ComponentModel.ISupportInitialize).EndInit()
        Me.tabPendingPick.ResumeLayout(False)
        Me.GroupBox2.ResumeLayout(False)
        Me.TabControl1.ResumeLayout(False)
        Me.tabPicked.ResumeLayout(False)
        CType(Me.dgvpicked, System.ComponentModel.ISupportInitialize).EndInit()
        Me.tabPickedList.ResumeLayout(False)
        CType(Me.dgpickedlist, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        Me.ResumeLayout(False)

    End Sub

    Friend WithEvents txtWtrangeto As TextBox
    Friend WithEvents Label15 As Label
    Friend WithEvents txtproddate As TextBox
    Friend WithEvents txtfgsize As TextBox
    Friend WithEvents Label14 As Label
    Friend WithEvents txtBatches As TextBox
    Friend WithEvents Label13 As Label
    Friend WithEvents txtFGDesc As TextBox
    Friend WithEvents txtFGitemkey As TextBox
    Friend WithEvents Label12 As Label
    Friend WithEvents Button8 As Button
    Friend WithEvents btnSearchBatchNo As Button
    Friend WithEvents btnSearchRunNo As Button
    Friend WithEvents txtRemainingQty As TextBox
    Friend WithEvents Label11 As Label
    Friend WithEvents txtTotalNeeded As TextBox
    Friend WithEvents Label10 As Label
    Friend WithEvents txtWtrangefrom As TextBox
    Friend WithEvents Label9 As Label
    Friend WithEvents txtWt As TextBox
    Friend WithEvents Label8 As Label
    Friend WithEvents txtBagWt As TextBox
    Friend WithEvents Label7 As Label
    Friend WithEvents txtbin As TextBox
    Friend WithEvents Label6 As Label
    Friend WithEvents txtlotno As TextBox
    Friend WithEvents Label5 As Label
    Friend WithEvents txtDesc As TextBox
    Friend WithEvents Label4 As Label
    Friend WithEvents txtItemKey As TextBox
    Friend WithEvents Label3 As Label
    Friend WithEvents txtBatchno As TextBox
    Friend WithEvents Label2 As Label
    Friend WithEvents Label1 As Label
    Friend WithEvents txtRunNo As TextBox
    Friend WithEvents dgvtopick As DataGridView
    Friend WithEvents tabPendingPick As TabPage
    Friend WithEvents txtsuggestedlot As TextBox
    Friend WithEvents Label18 As Label
    Friend WithEvents txtrmtopartial As TextBox
    Friend WithEvents Label17 As Label
    Friend WithEvents ComboBox1 As ComboBox
    Friend WithEvents Label16 As Label
    Friend WithEvents GroupBox2 As GroupBox
    Friend WithEvents TabControl1 As TabControl
    Friend WithEvents tabPicked As TabPage
    Friend WithEvents dgvpicked As DataGridView
    Friend WithEvents tabPickedList As TabPage
    Friend WithEvents dgpickedlist As DataGridView
    Friend WithEvents GroupBox1 As GroupBox
    Friend WithEvents pitem As DataGridViewTextBoxColumn
    Friend WithEvents pbatchno As DataGridViewTextBoxColumn
    Friend WithEvents ppartialdata As DataGridViewTextBoxColumn
    Friend WithEvents pwt As DataGridViewTextBoxColumn
    Friend WithEvents pbalanced As DataGridViewTextBoxColumn
    Friend WithEvents pLotNo As DataGridViewTextBoxColumn
    Friend WithEvents slotno As DataGridViewTextBoxColumn
    Friend WithEvents pBinNo As DataGridViewTextBoxColumn
    Friend WithEvents pBagWt As DataGridViewTextBoxColumn
    Friend WithEvents pwtrangefrom As DataGridViewTextBoxColumn
    Friend WithEvents pwtrangeto As DataGridViewTextBoxColumn
    Friend WithEvents pdesc As DataGridViewTextBoxColumn
    Friend WithEvents cmbAllergen As ComboBox
    Friend WithEvents Label19 As Label
    Friend WithEvents ToolTip1 As ToolTip
    Friend WithEvents item As DataGridViewTextBoxColumn
    Friend WithEvents batchno As DataGridViewTextBoxColumn
    Friend WithEvents partialdata As DataGridViewTextBoxColumn
    Friend WithEvents wt As DataGridViewTextBoxColumn
    Friend WithEvents balanced As DataGridViewTextBoxColumn
    Friend WithEvents LotNo As DataGridViewTextBoxColumn
    Friend WithEvents tp_slotno As DataGridViewTextBoxColumn
    Friend WithEvents BinNo As DataGridViewTextBoxColumn
    Friend WithEvents BagWt As DataGridViewTextBoxColumn
    Friend WithEvents wtrangefrom As DataGridViewTextBoxColumn
    Friend WithEvents wtrangeto As DataGridViewTextBoxColumn
    Friend WithEvents desc As DataGridViewTextBoxColumn
    Friend WithEvents allergen As DataGridViewTextBoxColumn
    Friend WithEvents nBulk As DataGridViewTextBoxColumn
    Friend WithEvents btnlotno As Button
    Friend WithEvents btnitemkey As Button
    Friend WithEvents btnprintrun As Button
    Friend WithEvents btnprintbatch As Button
    Friend WithEvents DataGridViewTextBoxColumn6 As DataGridViewTextBoxColumn
    Friend WithEvents DataGridViewTextBoxColumn7 As DataGridViewTextBoxColumn
    Friend WithEvents DataGridViewTextBoxColumn8 As DataGridViewTextBoxColumn
    Friend WithEvents DataGridViewTextBoxColumn9 As DataGridViewTextBoxColumn
    Friend WithEvents partial_id As DataGridViewTextBoxColumn
    Friend WithEvents pick_allergen As DataGridViewTextBoxColumn
    Friend WithEvents picked_expiry As DataGridViewTextBoxColumn
End Class
