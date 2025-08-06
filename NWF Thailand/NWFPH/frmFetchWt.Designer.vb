<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class frmFetchWt
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()>
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
    <System.Diagnostics.DebuggerStepThrough()>
    Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.ProgressBar1 = New System.Windows.Forms.ProgressBar()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.txtRunNo = New System.Windows.Forms.TextBox()
        Me.txtBatchNo = New System.Windows.Forms.TextBox()
        Me.txtItemBarcode = New System.Windows.Forms.TextBox()
        Me.txtreqwt = New System.Windows.Forms.TextBox()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.txtwtfr = New System.Windows.Forms.TextBox()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.Label8 = New System.Windows.Forms.Label()
        Me.txtwtto = New System.Windows.Forms.TextBox()
        Me.txttotalwt = New System.Windows.Forms.TextBox()
        Me.Label6 = New System.Windows.Forms.Label()
        Me.txtactualwt = New System.Windows.Forms.TextBox()
        Me.Label7 = New System.Windows.Forms.Label()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.DataGridView1 = New System.Windows.Forms.DataGridView()
        Me.options = New System.Windows.Forms.DataGridViewButtonColumn()
        Me.ItemKey = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.LotNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Expiry = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Qty = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.batchno = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.runno = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.statflag = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.partialid = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.GroupBox3 = New System.Windows.Forms.GroupBox()
        Me.Label9 = New System.Windows.Forms.Label()
        Me.txtstockonhand = New System.Windows.Forms.TextBox()
        Me.itemBarcode = New System.Windows.Forms.Button()
        Me.btnBatchno = New System.Windows.Forms.Button()
        Me.btnRunno = New System.Windows.Forms.Button()
        Me.Button1 = New System.Windows.Forms.Button()
        Me.GroupBox4 = New System.Windows.Forms.GroupBox()
        Me.Button2 = New System.Windows.Forms.Button()
        Me.Button5 = New System.Windows.Forms.Button()
        Me.lblactwt = New System.Windows.Forms.Label()
        Me.Timer1 = New System.Windows.Forms.Timer(Me.components)
        Me.Timer2 = New System.Windows.Forms.Timer(Me.components)
        Me.GroupBox1.SuspendLayout()
        Me.GroupBox2.SuspendLayout()
        CType(Me.DataGridView1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox3.SuspendLayout()
        Me.GroupBox4.SuspendLayout()
        Me.SuspendLayout()
        '
        'ProgressBar1
        '
        Me.ProgressBar1.BackColor = System.Drawing.SystemColors.Info
        Me.ProgressBar1.ForeColor = System.Drawing.SystemColors.MenuHighlight
        Me.ProgressBar1.Location = New System.Drawing.Point(3, 15)
        Me.ProgressBar1.Name = "ProgressBar1"
        Me.ProgressBar1.Size = New System.Drawing.Size(1193, 71)
        Me.ProgressBar1.Step = 1
        Me.ProgressBar1.TabIndex = 0
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label1.Location = New System.Drawing.Point(8, 15)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(126, 37)
        Me.Label1.TabIndex = 1
        Me.Label1.Text = "Run No"
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label2.Location = New System.Drawing.Point(8, 65)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(150, 37)
        Me.Label2.TabIndex = 2
        Me.Label2.Text = "Batch No"
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label3.Location = New System.Drawing.Point(6, 113)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(206, 37)
        Me.Label3.TabIndex = 3
        Me.Label3.Text = "Item Barcode"
        '
        'txtRunNo
        '
        Me.txtRunNo.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtRunNo.Location = New System.Drawing.Point(224, 11)
        Me.txtRunNo.Name = "txtRunNo"
        Me.txtRunNo.ReadOnly = True
        Me.txtRunNo.Size = New System.Drawing.Size(300, 44)
        Me.txtRunNo.TabIndex = 4
        '
        'txtBatchNo
        '
        Me.txtBatchNo.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtBatchNo.Location = New System.Drawing.Point(224, 61)
        Me.txtBatchNo.Name = "txtBatchNo"
        Me.txtBatchNo.ReadOnly = True
        Me.txtBatchNo.Size = New System.Drawing.Size(300, 44)
        Me.txtBatchNo.TabIndex = 5
        '
        'txtItemBarcode
        '
        Me.txtItemBarcode.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtItemBarcode.Location = New System.Drawing.Point(224, 110)
        Me.txtItemBarcode.Name = "txtItemBarcode"
        Me.txtItemBarcode.ReadOnly = True
        Me.txtItemBarcode.Size = New System.Drawing.Size(300, 44)
        Me.txtItemBarcode.TabIndex = 6
        '
        'txtreqwt
        '
        Me.txtreqwt.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtreqwt.Location = New System.Drawing.Point(262, 34)
        Me.txtreqwt.Name = "txtreqwt"
        Me.txtreqwt.ReadOnly = True
        Me.txtreqwt.Size = New System.Drawing.Size(386, 44)
        Me.txtreqwt.TabIndex = 8
        Me.txtreqwt.Text = "0"
        Me.txtreqwt.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label4.Location = New System.Drawing.Point(6, 40)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(254, 37)
        Me.Label4.TabIndex = 7
        Me.Label4.Text = "Required Weight"
        '
        'txtwtfr
        '
        Me.txtwtfr.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtwtfr.Location = New System.Drawing.Point(261, 86)
        Me.txtwtfr.Name = "txtwtfr"
        Me.txtwtfr.ReadOnly = True
        Me.txtwtfr.Size = New System.Drawing.Size(164, 44)
        Me.txtwtfr.TabIndex = 10
        Me.txtwtfr.Text = "0"
        Me.txtwtfr.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label5
        '
        Me.Label5.AutoSize = True
        Me.Label5.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label5.Location = New System.Drawing.Point(6, 90)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(219, 37)
        Me.Label5.TabIndex = 9
        Me.Label5.Text = "Weight Range"
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.Label8)
        Me.GroupBox1.Controls.Add(Me.txtwtto)
        Me.GroupBox1.Controls.Add(Me.txttotalwt)
        Me.GroupBox1.Controls.Add(Me.Label6)
        Me.GroupBox1.Controls.Add(Me.txtactualwt)
        Me.GroupBox1.Controls.Add(Me.Label7)
        Me.GroupBox1.Controls.Add(Me.txtwtfr)
        Me.GroupBox1.Controls.Add(Me.Label4)
        Me.GroupBox1.Controls.Add(Me.txtreqwt)
        Me.GroupBox1.Controls.Add(Me.Label5)
        Me.GroupBox1.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.GroupBox1.Location = New System.Drawing.Point(541, 92)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(655, 276)
        Me.GroupBox1.TabIndex = 11
        Me.GroupBox1.TabStop = False
        Me.GroupBox1.Text = "Item Pick Details"
        '
        'Label8
        '
        Me.Label8.AutoSize = True
        Me.Label8.BackColor = System.Drawing.Color.Transparent
        Me.Label8.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label8.Location = New System.Drawing.Point(426, 90)
        Me.Label8.Name = "Label8"
        Me.Label8.Size = New System.Drawing.Size(62, 37)
        Me.Label8.TabIndex = 16
        Me.Label8.Text = "TO"
        '
        'txtwtto
        '
        Me.txtwtto.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtwtto.Location = New System.Drawing.Point(490, 86)
        Me.txtwtto.Name = "txtwtto"
        Me.txtwtto.ReadOnly = True
        Me.txtwtto.Size = New System.Drawing.Size(158, 44)
        Me.txtwtto.TabIndex = 15
        Me.txtwtto.Text = "0"
        Me.txtwtto.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'txttotalwt
        '
        Me.txttotalwt.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txttotalwt.Location = New System.Drawing.Point(262, 188)
        Me.txttotalwt.Name = "txttotalwt"
        Me.txttotalwt.ReadOnly = True
        Me.txttotalwt.Size = New System.Drawing.Size(386, 44)
        Me.txttotalwt.TabIndex = 14
        Me.txttotalwt.Text = "0"
        Me.txttotalwt.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label6
        '
        Me.Label6.AutoSize = True
        Me.Label6.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label6.Location = New System.Drawing.Point(6, 141)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(216, 37)
        Me.Label6.TabIndex = 11
        Me.Label6.Text = "Actual Weight"
        '
        'txtactualwt
        '
        Me.txtactualwt.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtactualwt.Location = New System.Drawing.Point(262, 138)
        Me.txtactualwt.Name = "txtactualwt"
        Me.txtactualwt.Size = New System.Drawing.Size(386, 44)
        Me.txtactualwt.TabIndex = 12
        Me.txtactualwt.Text = "0"
        Me.txtactualwt.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label7
        '
        Me.Label7.AutoSize = True
        Me.Label7.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label7.Location = New System.Drawing.Point(1, 191)
        Me.Label7.Name = "Label7"
        Me.Label7.Size = New System.Drawing.Size(198, 37)
        Me.Label7.TabIndex = 13
        Me.Label7.Text = "Total Weight"
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.Add(Me.DataGridView1)
        Me.GroupBox2.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.GroupBox2.Location = New System.Drawing.Point(5, 385)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(1184, 299)
        Me.GroupBox2.TabIndex = 12
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "Current Picked Details"
        '
        'DataGridView1
        '
        Me.DataGridView1.AllowUserToAddRows = False
        Me.DataGridView1.AllowUserToDeleteRows = False
        Me.DataGridView1.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.DataGridView1.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.options, Me.ItemKey, Me.LotNo, Me.Expiry, Me.Qty, Me.batchno, Me.runno, Me.statflag, Me.partialid})
        Me.DataGridView1.Dock = System.Windows.Forms.DockStyle.Fill
        Me.DataGridView1.Location = New System.Drawing.Point(3, 40)
        Me.DataGridView1.Name = "DataGridView1"
        Me.DataGridView1.ReadOnly = True
        Me.DataGridView1.RowHeadersWidth = 51
        Me.DataGridView1.RowTemplate.Height = 30
        Me.DataGridView1.Size = New System.Drawing.Size(1178, 256)
        Me.DataGridView1.TabIndex = 0
        '
        'options
        '
        Me.options.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.options.HeaderText = "Action"
        Me.options.MinimumWidth = 6
        Me.options.Name = "options"
        Me.options.ReadOnly = True
        Me.options.Resizable = System.Windows.Forms.DataGridViewTriState.[True]
        Me.options.Visible = False
        '
        'ItemKey
        '
        Me.ItemKey.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.ItemKey.HeaderText = "Item Key"
        Me.ItemKey.MinimumWidth = 6
        Me.ItemKey.Name = "ItemKey"
        Me.ItemKey.ReadOnly = True
        '
        'LotNo
        '
        Me.LotNo.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.LotNo.HeaderText = "Lot No"
        Me.LotNo.MinimumWidth = 6
        Me.LotNo.Name = "LotNo"
        Me.LotNo.ReadOnly = True
        '
        'Expiry
        '
        Me.Expiry.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.Expiry.HeaderText = "Expiry"
        Me.Expiry.MinimumWidth = 6
        Me.Expiry.Name = "Expiry"
        Me.Expiry.ReadOnly = True
        '
        'Qty
        '
        Me.Qty.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.Qty.HeaderText = "Quantity"
        Me.Qty.MinimumWidth = 6
        Me.Qty.Name = "Qty"
        Me.Qty.ReadOnly = True
        '
        'batchno
        '
        Me.batchno.HeaderText = "batchno"
        Me.batchno.MinimumWidth = 6
        Me.batchno.Name = "batchno"
        Me.batchno.ReadOnly = True
        Me.batchno.Visible = False
        Me.batchno.Width = 125
        '
        'runno
        '
        Me.runno.HeaderText = "runno"
        Me.runno.MinimumWidth = 6
        Me.runno.Name = "runno"
        Me.runno.ReadOnly = True
        Me.runno.Visible = False
        Me.runno.Width = 125
        '
        'statflag
        '
        Me.statflag.HeaderText = "statflag"
        Me.statflag.MinimumWidth = 6
        Me.statflag.Name = "statflag"
        Me.statflag.ReadOnly = True
        Me.statflag.Visible = False
        Me.statflag.Width = 125
        '
        'partialid
        '
        Me.partialid.HeaderText = "Column1"
        Me.partialid.MinimumWidth = 6
        Me.partialid.Name = "partialid"
        Me.partialid.ReadOnly = True
        Me.partialid.Visible = False
        Me.partialid.Width = 125
        '
        'GroupBox3
        '
        Me.GroupBox3.Controls.Add(Me.Label9)
        Me.GroupBox3.Controls.Add(Me.txtstockonhand)
        Me.GroupBox3.Controls.Add(Me.itemBarcode)
        Me.GroupBox3.Controls.Add(Me.btnBatchno)
        Me.GroupBox3.Controls.Add(Me.btnRunno)
        Me.GroupBox3.Controls.Add(Me.Button1)
        Me.GroupBox3.Controls.Add(Me.txtRunNo)
        Me.GroupBox3.Controls.Add(Me.Label1)
        Me.GroupBox3.Controls.Add(Me.Label2)
        Me.GroupBox3.Controls.Add(Me.Label3)
        Me.GroupBox3.Controls.Add(Me.txtBatchNo)
        Me.GroupBox3.Controls.Add(Me.txtItemBarcode)
        Me.GroupBox3.Location = New System.Drawing.Point(5, 92)
        Me.GroupBox3.Name = "GroupBox3"
        Me.GroupBox3.Size = New System.Drawing.Size(530, 276)
        Me.GroupBox3.TabIndex = 12
        Me.GroupBox3.TabStop = False
        '
        'Label9
        '
        Me.Label9.AutoSize = True
        Me.Label9.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label9.Location = New System.Drawing.Point(6, 163)
        Me.Label9.Name = "Label9"
        Me.Label9.Size = New System.Drawing.Size(204, 37)
        Me.Label9.TabIndex = 26
        Me.Label9.Text = "Available Qty"
        '
        'txtstockonhand
        '
        Me.txtstockonhand.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtstockonhand.Location = New System.Drawing.Point(224, 160)
        Me.txtstockonhand.Name = "txtstockonhand"
        Me.txtstockonhand.ReadOnly = True
        Me.txtstockonhand.Size = New System.Drawing.Size(300, 44)
        Me.txtstockonhand.TabIndex = 27
        '
        'itemBarcode
        '
        Me.itemBarcode.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.itemBarcode.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_search_32
        Me.itemBarcode.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.itemBarcode.Enabled = False
        Me.itemBarcode.Location = New System.Drawing.Point(384, 97)
        Me.itemBarcode.Name = "itemBarcode"
        Me.itemBarcode.Size = New System.Drawing.Size(31, 29)
        Me.itemBarcode.TabIndex = 25
        Me.itemBarcode.UseVisualStyleBackColor = True
        Me.itemBarcode.Visible = False
        '
        'btnBatchno
        '
        Me.btnBatchno.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.btnBatchno.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_search_32
        Me.btnBatchno.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.btnBatchno.Enabled = False
        Me.btnBatchno.Location = New System.Drawing.Point(384, 56)
        Me.btnBatchno.Name = "btnBatchno"
        Me.btnBatchno.Size = New System.Drawing.Size(31, 29)
        Me.btnBatchno.TabIndex = 24
        Me.btnBatchno.UseVisualStyleBackColor = True
        Me.btnBatchno.Visible = False
        '
        'btnRunno
        '
        Me.btnRunno.AutoSizeMode = System.Windows.Forms.AutoSizeMode.GrowAndShrink
        Me.btnRunno.BackgroundImage = Global.NWFTH.My.Resources.Resources.icons8_search_32
        Me.btnRunno.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Zoom
        Me.btnRunno.Enabled = False
        Me.btnRunno.Location = New System.Drawing.Point(384, 16)
        Me.btnRunno.Name = "btnRunno"
        Me.btnRunno.Size = New System.Drawing.Size(31, 29)
        Me.btnRunno.TabIndex = 23
        Me.btnRunno.UseVisualStyleBackColor = True
        Me.btnRunno.Visible = False
        '
        'Button1
        '
        Me.Button1.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Button1.Image = Global.NWFTH.My.Resources.Resources.icons8_add_48
        Me.Button1.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.Button1.Location = New System.Drawing.Point(144, 209)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(228, 62)
        Me.Button1.TabIndex = 7
        Me.Button1.Text = "ADD LOT"
        Me.Button1.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        Me.Button1.UseVisualStyleBackColor = True
        Me.Button1.Visible = False
        '
        'GroupBox4
        '
        Me.GroupBox4.Controls.Add(Me.Button2)
        Me.GroupBox4.Controls.Add(Me.ProgressBar1)
        Me.GroupBox4.Controls.Add(Me.GroupBox1)
        Me.GroupBox4.Controls.Add(Me.Button5)
        Me.GroupBox4.Controls.Add(Me.GroupBox2)
        Me.GroupBox4.Controls.Add(Me.GroupBox3)
        Me.GroupBox4.Location = New System.Drawing.Point(8, 77)
        Me.GroupBox4.Margin = New System.Windows.Forms.Padding(2)
        Me.GroupBox4.Name = "GroupBox4"
        Me.GroupBox4.Padding = New System.Windows.Forms.Padding(2)
        Me.GroupBox4.Size = New System.Drawing.Size(1201, 775)
        Me.GroupBox4.TabIndex = 28
        Me.GroupBox4.TabStop = False
        '
        'Button2
        '
        Me.Button2.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Button2.Image = Global.NWFTH.My.Resources.Resources.icons8_print_80
        Me.Button2.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.Button2.Location = New System.Drawing.Point(615, 690)
        Me.Button2.Name = "Button2"
        Me.Button2.Size = New System.Drawing.Size(243, 73)
        Me.Button2.TabIndex = 28
        Me.Button2.Text = "Save/Print"
        Me.Button2.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        Me.Button2.UseVisualStyleBackColor = True
        '
        'Button5
        '
        Me.Button5.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Button5.Image = Global.NWFTH.My.Resources.Resources.icons8_save_80
        Me.Button5.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.Button5.Location = New System.Drawing.Point(297, 690)
        Me.Button5.Name = "Button5"
        Me.Button5.Size = New System.Drawing.Size(221, 73)
        Me.Button5.TabIndex = 26
        Me.Button5.Text = "        SAVE"
        Me.Button5.UseVisualStyleBackColor = True
        '
        'lblactwt
        '
        Me.lblactwt.AutoSize = True
        Me.lblactwt.Font = New System.Drawing.Font("Microsoft Sans Serif", 50.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblactwt.Location = New System.Drawing.Point(555, 2)
        Me.lblactwt.Name = "lblactwt"
        Me.lblactwt.Size = New System.Drawing.Size(70, 76)
        Me.lblactwt.TabIndex = 29
        Me.lblactwt.Text = "0"
        Me.lblactwt.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'Timer1
        '
        '
        'Timer2
        '
        Me.Timer2.Interval = 4000
        '
        'frmFetchWt
        '
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Inherit
        Me.BackColor = System.Drawing.SystemColors.MenuHighlight
        Me.ClientSize = New System.Drawing.Size(1220, 863)
        Me.Controls.Add(Me.lblactwt)
        Me.Controls.Add(Me.GroupBox4)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow
        Me.MinimizeBox = False
        Me.Name = "frmFetchWt"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Fetch Weight"
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        Me.GroupBox2.ResumeLayout(False)
        CType(Me.DataGridView1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox3.ResumeLayout(False)
        Me.GroupBox3.PerformLayout()
        Me.GroupBox4.ResumeLayout(False)
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub

    Friend WithEvents ProgressBar1 As ProgressBar
    Friend WithEvents Label1 As Label
    Friend WithEvents Label2 As Label
    Friend WithEvents Label3 As Label
    Friend WithEvents txtRunNo As TextBox
    Friend WithEvents txtBatchNo As TextBox
    Friend WithEvents txtItemBarcode As TextBox
    Friend WithEvents txtreqwt As TextBox
    Friend WithEvents Label4 As Label
    Friend WithEvents txtwtfr As TextBox
    Friend WithEvents Label5 As Label
    Friend WithEvents GroupBox1 As GroupBox
    Friend WithEvents txttotalwt As TextBox
    Friend WithEvents Label6 As Label
    Friend WithEvents txtactualwt As TextBox
    Friend WithEvents Label7 As Label
    Friend WithEvents GroupBox2 As GroupBox
    Friend WithEvents GroupBox3 As GroupBox
    Friend WithEvents Button1 As Button
    Friend WithEvents txtwtto As TextBox
    Friend WithEvents Label8 As Label
    Friend WithEvents itemBarcode As Button
    Friend WithEvents btnBatchno As Button
    Friend WithEvents btnRunno As Button
    Friend WithEvents Button5 As Button
    Friend WithEvents DataGridView1 As DataGridView
    Friend WithEvents GroupBox4 As GroupBox
    Friend WithEvents lblactwt As Label
    Friend WithEvents Button2 As Button
    Friend WithEvents Timer1 As Timer
    Friend WithEvents Label9 As Label
    Friend WithEvents txtstockonhand As TextBox
    Friend WithEvents options As DataGridViewButtonColumn
    Friend WithEvents ItemKey As DataGridViewTextBoxColumn
    Friend WithEvents LotNo As DataGridViewTextBoxColumn
    Friend WithEvents Expiry As DataGridViewTextBoxColumn
    Friend WithEvents Qty As DataGridViewTextBoxColumn
    Friend WithEvents batchno As DataGridViewTextBoxColumn
    Friend WithEvents runno As DataGridViewTextBoxColumn
    Friend WithEvents statflag As DataGridViewTextBoxColumn
    Friend WithEvents partialid As DataGridViewTextBoxColumn
    Friend WithEvents Timer2 As Timer
End Class
